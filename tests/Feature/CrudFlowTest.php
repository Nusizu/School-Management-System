<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Support\Format;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Pengujian akhir alur CRUD, upload foto, format tampilan, dan halaman error.
 *
 * Jalankan: php artisan test --filter=CrudFlowTest
 */
class CrudFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $teacherUser;
    private User $studentUser;
    private SchoolClass $class;
    private Subject $subject;

    /** @var string[] File foto hasil tes yang harus dibersihkan */
    private array $createdFiles = [];

    /** Pengaman: tes ini hanya boleh jalan di SQLite memori, tidak menyentuh MySQL asli. */
    protected function beforeRefreshingDatabase(): void
    {
        if (config('database.default') !== 'sqlite'
            || config('database.connections.sqlite.database') !== ':memory:') {
            $this->fail('Tes dihentikan demi keamanan: pastikan phpunit.xml memakai DB_CONNECTION=sqlite dan DB_DATABASE=:memory:.');
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        $this->admin = $this->makeUser('Admin Uji', 'admin@uji.test', User::ROLE_ADMIN);
        $this->teacherUser = $this->makeUser('Akun Guru', 'guru@uji.test', User::ROLE_TEACHER);
        $this->studentUser = $this->makeUser('Akun Siswa', 'siswa@uji.test', User::ROLE_STUDENT);

        $this->class = SchoolClass::create(['name' => '10 IPA 1']);
        $this->subject = Subject::create(['code' => 'MTK', 'name' => 'Matematika']);
    }

    protected function tearDown(): void
    {
        foreach ($this->createdFiles as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }

        parent::tearDown();
    }

    private function makeUser(string $name, string $email, string $role): User
    {
        return User::create(['name' => $name, 'email' => $email, 'password' => 'password', 'role' => $role]);
    }

    private function skipWithoutGd(): void
    {
        if (! extension_loaded('gd')) {
            $this->markTestSkipped('Ekstensi GD tidak aktif, tes upload foto dilewati.');
        }
    }

    // ------------------------------------------------------------------
    // Teachers
    // ------------------------------------------------------------------

    public function test_teacher_crud_flow(): void
    {
        $data = [
            'name' => 'Dewi Lestari, S.Pd.',
            'subject_id' => $this->subject->id,
            'employee_number' => '198501012010012002',
            'phone' => '081234567890',
            'address' => 'Jl. Pemuda No. 10, Kota Semarang',
            'user_id' => $this->teacherUser->id,
        ];

        $this->actingAs($this->admin)->post(route('teachers.store'), $data)
            ->assertRedirect(route('teachers.index'));
        $this->assertDatabaseHas('tbl_teachers', ['name' => 'Dewi Lestari, S.Pd.', 'archived' => 0]);

        $teacher = Teacher::where('employee_number', '198501012010012002')->firstOrFail();

        // Validasi: mapel wajib, nomor induk dan akun tidak boleh ganda
        $this->actingAs($this->admin)->post(route('teachers.store'), ['name' => 'Tanpa Mapel'])
            ->assertSessionHasErrors('subject_id');
        $this->actingAs($this->admin)->post(route('teachers.store'), [
            'name' => 'Nomor Ganda', 'subject_id' => $this->subject->id, 'employee_number' => '198501012010012002',
        ])->assertSessionHasErrors('employee_number');
        $this->actingAs($this->admin)->post(route('teachers.store'), [
            'name' => 'Akun Ganda', 'subject_id' => $this->subject->id, 'user_id' => $this->teacherUser->id,
        ])->assertSessionHasErrors('user_id');

        // Akun dengan role student tidak boleh ditautkan ke guru
        $this->actingAs($this->admin)->post(route('teachers.store'), [
            'name' => 'Akun Salah Role', 'subject_id' => $this->subject->id, 'user_id' => $this->studentUser->id,
        ])->assertSessionHasErrors('user_id');

        // Ubah nama: slug ikut berubah
        $this->actingAs($this->admin)->put(route('teachers.update', $teacher), [
            'name' => 'Nama Baru, M.Pd.',
            'subject_id' => $this->subject->id,
            'employee_number' => '198501012010012002',
            'user_id' => $this->teacherUser->id,
        ])->assertRedirect(route('teachers.index'));

        $teacher->refresh();
        $this->assertSame('Nama Baru, M.Pd.', $teacher->name);
        $this->assertStringStartsWith('nama-baru-mpd-', $teacher->slug);

        // Hapus: soft delete
        $this->actingAs($this->admin)->delete(route('teachers.destroy', $teacher))
            ->assertRedirect(route('teachers.index'));
        $this->assertDatabaseHas('tbl_teachers', ['id' => $teacher->id, 'archived' => 1, 'user_id' => null]);
    }

    public function test_archiving_student_and_teacher_frees_their_login_account(): void
    {
        $teacher = Teacher::create([
            'user_id' => $this->teacherUser->id, 'subject_id' => $this->subject->id, 'name' => 'Guru Tertaut',
        ]);
        $student = Student::create([
            'user_id' => $this->studentUser->id, 'class_id' => $this->class->id,
            'student_code' => 'STD-001', 'name' => 'Siswa Tertaut', 'status' => 'active',
        ]);

        $this->actingAs($this->admin)->delete(route('teachers.destroy', $teacher));
        $this->actingAs($this->admin)->delete(route('students.destroy', $student));

        $this->assertDatabaseHas('tbl_teachers', ['id' => $teacher->id, 'archived' => 1, 'user_id' => null]);
        $this->assertDatabaseHas('tbl_students', ['id' => $student->id, 'archived' => 1, 'user_id' => null]);

        // Akun yang sama bisa ditautkan ke data baru
        $this->actingAs($this->admin)->post(route('teachers.store'), [
            'name' => 'Guru Pengganti', 'subject_id' => $this->subject->id, 'user_id' => $this->teacherUser->id,
        ])->assertSessionHasNoErrors();
        $this->actingAs($this->admin)->post(route('students.store'), [
            'name' => 'Siswa Pengganti', 'class_id' => $this->class->id, 'status' => 'active', 'user_id' => $this->studentUser->id,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('tbl_teachers', ['name' => 'Guru Pengganti', 'user_id' => $this->teacherUser->id]);
        $this->assertDatabaseHas('tbl_students', ['name' => 'Siswa Pengganti', 'user_id' => $this->studentUser->id]);
    }

    // ------------------------------------------------------------------
    // Classes
    // ------------------------------------------------------------------

    public function test_class_crud_flow(): void
    {
        $this->actingAs($this->admin)->post(route('classes.store'), ['name' => 'Kelas Baru', 'description' => 'Ruang 101'])
            ->assertRedirect(route('classes.index'));
        $class = SchoolClass::where('name', 'Kelas Baru')->firstOrFail();

        // Nama kelas aktif tidak boleh ganda
        $this->actingAs($this->admin)->post(route('classes.store'), ['name' => '10 IPA 1'])
            ->assertSessionHasErrors('name');
        $this->actingAs($this->admin)->post(route('classes.store'), [])
            ->assertSessionHasErrors('name');

        $this->actingAs($this->admin)->put(route('classes.update', $class), ['name' => 'Kelas Baru 2'])
            ->assertRedirect(route('classes.index'));
        $this->assertDatabaseHas('tbl_classes', ['id' => $class->id, 'name' => 'Kelas Baru 2']);

        // Nama berubah sehingga slug ikut berubah: muat ulang model sebelum dipakai membentuk URL
        $class->refresh();

        $this->actingAs($this->admin)->delete(route('classes.destroy', $class))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('tbl_classes', ['id' => $class->id, 'archived' => 1]);

        // Setelah dihapus, nama yang sama boleh dipakai lagi
        $this->actingAs($this->admin)->post(route('classes.store'), ['name' => 'Kelas Baru 2'])
            ->assertSessionHasNoErrors();
    }

    // ------------------------------------------------------------------
    // Subjects
    // ------------------------------------------------------------------

    public function test_subject_crud_flow(): void
    {
        // Kode diketik huruf kecil, tersimpan huruf besar
        $this->actingAs($this->admin)->post(route('subjects.store'), ['code' => 'bio', 'name' => 'Biologi'])
            ->assertRedirect(route('subjects.index'));
        $this->assertDatabaseHas('tbl_subjects', ['code' => 'BIO', 'name' => 'Biologi']);

        // Kode ganda ditolak walau beda huruf besar/kecil
        $this->actingAs($this->admin)->post(route('subjects.store'), ['code' => 'bio', 'name' => 'Lain'])
            ->assertSessionHasErrors('code');
        $this->actingAs($this->admin)->post(route('subjects.store'), ['code' => str_repeat('A', 21), 'name' => 'Terlalu Panjang'])
            ->assertSessionHasErrors('code');

        $subject = Subject::where('code', 'BIO')->firstOrFail();

        // Ubah dengan kode yang sama (milik sendiri) tidak dianggap ganda
        $this->actingAs($this->admin)->put(route('subjects.update', $subject), ['code' => 'BIO', 'name' => 'Biologi Dasar'])
            ->assertRedirect(route('subjects.index'));
        $this->assertDatabaseHas('tbl_subjects', ['id' => $subject->id, 'name' => 'Biologi Dasar']);

        // Nama berubah sehingga slug ikut berubah: muat ulang model sebelum dipakai membentuk URL
        $subject->refresh();

        $this->actingAs($this->admin)->delete(route('subjects.destroy', $subject))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('tbl_subjects', ['id' => $subject->id, 'archived' => 1]);
    }

    // ------------------------------------------------------------------
    // Users
    // ------------------------------------------------------------------

    public function test_user_crud_flow(): void
    {
        $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Pengguna Baru', 'email' => 'baru@uji.test', 'role' => 'teacher',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertRedirect(route('users.index'));

        $user = User::where('email', 'baru@uji.test')->firstOrFail();
        $this->assertTrue(Hash::check('rahasia123', $user->password));

        // Validasi password dan email
        $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Pendek', 'email' => 'pendek@uji.test', 'role' => 'student',
            'password' => 'abc', 'password_confirmation' => 'abc',
        ])->assertSessionHasErrors('password');
        $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Beda', 'email' => 'beda@uji.test', 'role' => 'student',
            'password' => 'rahasia123', 'password_confirmation' => 'lainnya123',
        ])->assertSessionHasErrors('password');
        $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'Email Ganda', 'email' => 'baru@uji.test', 'role' => 'student',
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ])->assertSessionHasErrors('email');

        // Ubah tanpa mengisi password: password lama tetap
        $this->actingAs($this->admin)->put(route('users.update', $user), [
            'name' => 'Nama Diubah', 'email' => 'baru@uji.test', 'role' => 'teacher',
        ])->assertRedirect(route('users.index'));
        $user->refresh();
        $this->assertSame('Nama Diubah', $user->name);
        $this->assertTrue(Hash::check('rahasia123', $user->password));

        // Ubah dengan password baru
        $this->actingAs($this->admin)->put(route('users.update', $user), [
            'name' => 'Nama Diubah', 'email' => 'baru@uji.test', 'role' => 'teacher',
            'password' => 'baru12345', 'password_confirmation' => 'baru12345',
        ])->assertRedirect(route('users.index'));
        $this->assertTrue(Hash::check('baru12345', $user->fresh()->password));

        $this->actingAs($this->admin)->delete(route('users.destroy', $user))
            ->assertSessionHas('success');
        $this->assertDatabaseHas('tbl_users', ['id' => $user->id, 'archived' => 1]);
    }

    // ------------------------------------------------------------------
    // Upload foto siswa
    // ------------------------------------------------------------------

    public function test_student_photo_upload_rules_and_replacement(): void
    {
        $this->skipWithoutGd();

        $base = ['class_id' => $this->class->id, 'status' => 'active'];

        // Bukan gambar: ditolak
        $this->actingAs($this->admin)->post(route('students.store'), $base + [
            'name' => 'Foto PDF',
            'photo' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('photo');

        // Terlalu besar (3000 KB > 2048 KB): ditolak
        $this->actingAs($this->admin)->post(route('students.store'), $base + [
            'name' => 'Foto Besar',
            'photo' => UploadedFile::fake()->image('besar.jpg')->size(3000),
        ])->assertSessionHasErrors('photo');

        // Foto valid: nama file mengikuti format YmdHis-nama-file
        $this->actingAs($this->admin)->post(route('students.store'), $base + [
            'name' => 'Budi Foto',
            'photo' => UploadedFile::fake()->image('Foto Budi.jpg', 300, 300)->size(500),
        ])->assertRedirect(route('students.index'));

        $student = Student::where('name', 'Budi Foto')->firstOrFail();
        $this->assertMatchesRegularExpression('#^uploads/students/\d{14}-foto-budi\.jpe?g$#', $student->img_url);

        $oldPath = public_path($student->img_url);
        $this->createdFiles[] = $oldPath;
        $this->assertFileExists($oldPath);

        // Ganti foto: file lama dihapus, file baru tersimpan
        $this->actingAs($this->admin)->put(route('students.update', $student), $base + [
            'name' => 'Budi Foto',
            'photo' => UploadedFile::fake()->image('Foto Baru.png', 300, 300)->size(400),
        ])->assertRedirect(route('students.index'));

        $student->refresh();
        $newPath = public_path($student->img_url);
        $this->createdFiles[] = $newPath;

        $this->assertFileDoesNotExist($oldPath);
        $this->assertFileExists($newPath);
        $this->assertNotSame($oldPath, $newPath);
    }

    // ------------------------------------------------------------------
    // Tampilan: pencarian, pagination, breadcrumbs, format, halaman error
    // ------------------------------------------------------------------

    public function test_student_search_filters_the_list(): void
    {
        Student::create(['class_id' => $this->class->id, 'student_code' => 'STD-001', 'name' => 'Andi Pratama', 'status' => 'active']);
        Student::create(['class_id' => $this->class->id, 'student_code' => 'STD-002', 'name' => 'Budi Hartono', 'status' => 'active']);

        $this->actingAs($this->admin)->get(route('students.index', ['search' => 'Andi']))
            ->assertOk()
            ->assertSee('Andi Pratama')
            ->assertDontSee('Budi Hartono');

        // Pencarian juga mengenali kode siswa
        $this->actingAs($this->admin)->get(route('students.index', ['search' => 'STD-002']))
            ->assertOk()
            ->assertSee('Budi Hartono')
            ->assertDontSee('Andi Pratama');
    }

    public function test_pagination_is_in_indonesian(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            Student::create([
                'class_id' => $this->class->id,
                'student_code' => sprintf('STD-%03d', $i),
                'name' => 'Siswa Nomor '.$i,
                'status' => 'active',
            ]);
        }

        $this->actingAs($this->admin)->get(route('students.index'))
            ->assertOk()
            ->assertSee('Menampilkan')
            ->assertSee('Berikutnya');
    }

    public function test_breadcrumbs_and_validation_messages_are_indonesian(): void
    {
        $student = Student::create([
            'class_id' => $this->class->id, 'student_code' => 'STD-001', 'name' => 'Rina Putri', 'status' => 'active',
        ]);

        $this->actingAs($this->admin)->get(route('students.edit', $student))
            ->assertOk()
            ->assertSee('aria-label="Breadcrumb"', false)
            ->assertSee('aria-current="page"', false)
            ->assertSee('Rina Putri');

        $this->actingAs($this->admin)
            ->post(route('students.store'), ['class_id' => $this->class->id, 'status' => 'active'])
            ->assertSessionHasErrors(['name' => 'Nama wajib diisi.']);
    }

    public function test_format_helpers_use_indonesian_style(): void
    {
        $this->assertSame('1 Feb 2024', Format::date('2024-02-01'));
        $this->assertSame('-', Format::date(null));
        $this->assertSame('10.000', Format::number(10000));
        $this->assertSame('1.250', Format::number(1250));
        $this->assertSame('Minggu, 15 November 2026', Format::longDate('2026-11-15'));
    }

    public function test_error_pages_are_in_indonesian(): void
    {
        $this->get('/halaman-yang-tidak-ada')
            ->assertNotFound()
            ->assertSee('Halaman Tidak Ditemukan');

        $this->actingAs($this->teacherUser)->get(route('users.index'))
            ->assertForbidden()
            ->assertSee('Akses Ditolak');
    }
}
