<?php

namespace Tests\Feature;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengecekan hak akses admin, teacher, dan student pada seluruh fitur.
 *
 * Jalankan: php artisan test --filter=RoleAccessTest
 */
class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $teacherUser;
    private User $studentUser;
    private SchoolClass $class;
    private Subject $subject;
    private Teacher $teacherRecord;
    private Student $studentRecord;

    /**
     * Pengaman: RefreshDatabase menghapus semua tabel, jadi tes ini hanya boleh
     * berjalan di SQLite memori dan tidak pernah menyentuh database MySQL asli.
     */
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
        $this->teacherUser = $this->makeUser('Guru Uji', 'guru@uji.test', User::ROLE_TEACHER);
        $this->studentUser = $this->makeUser('Siswa Uji', 'siswa@uji.test', User::ROLE_STUDENT);

        $this->class = SchoolClass::create(['name' => '10 IPA 1', 'description' => 'Kelas uji']);
        $this->subject = Subject::create(['code' => 'MTK', 'name' => 'Matematika']);

        $this->teacherRecord = Teacher::create([
            'user_id' => $this->teacherUser->id,
            'subject_id' => $this->subject->id,
            'employee_number' => '198001012005011001',
            'name' => 'Guru Uji',
        ]);

        $this->studentRecord = Student::create([
            'user_id' => $this->studentUser->id,
            'class_id' => $this->class->id,
            'student_code' => 'STD-001',
            'name' => 'Siswa Uji',
            'status' => 'active',
        ]);
    }

    private function makeUser(string $name, string $email, string $role): User
    {
        return User::create([
            'name' => $name,
            'email' => $email,
            'password' => 'password',
            'role' => $role,
        ]);
    }

    // ------------------------------------------------------------------
    // Tamu (belum login)
    // ------------------------------------------------------------------

    public function test_guest_is_redirected_to_login(): void
    {
        foreach ([
            route('dashboard'),
            route('profile.edit'),
            route('students.index'),
            route('teachers.index'),
            route('classes.index'),
            route('subjects.index'),
            route('users.index'),
        ] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }

        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk();
    }

    // ------------------------------------------------------------------
    // Matriks halaman: [url, admin, teacher, student]
    // ------------------------------------------------------------------

    public function test_pages_follow_the_role_matrix(): void
    {
        $matrix = [
            [route('dashboard'), 200, 200, 200],
            [route('profile.edit'), 200, 200, 200],

            [route('users.index'), 200, 403, 403],
            [route('users.create'), 200, 403, 403],
            [route('users.edit', $this->teacherUser), 200, 403, 403],

            [route('students.index'), 200, 200, 403],
            [route('students.create'), 200, 403, 403],
            [route('students.edit', $this->studentRecord), 200, 403, 403],

            [route('teachers.index'), 200, 200, 403],
            [route('teachers.create'), 200, 403, 403],
            [route('teachers.edit', $this->teacherRecord), 200, 403, 403],

            [route('classes.index'), 200, 200, 403],
            [route('classes.create'), 200, 403, 403],
            [route('classes.edit', $this->class), 200, 403, 403],

            [route('subjects.index'), 200, 200, 403],
            [route('subjects.create'), 200, 403, 403],
            [route('subjects.edit', $this->subject), 200, 403, 403],
        ];

        $roles = [
            [$this->admin, 1],
            [$this->teacherUser, 2],
            [$this->studentUser, 3],
        ];

        foreach ($roles as [$user, $column]) {
            foreach ($matrix as $row) {
                $response = $this->actingAs($user)->get($row[0]);

                $this->assertSame(
                    $row[$column],
                    $response->getStatusCode(),
                    "Role {$user->role} membuka {$row[0]} seharusnya {$row[$column]}"
                );
            }
        }
    }

    // ------------------------------------------------------------------
    // Teacher dan student tidak boleh menambah, mengubah, atau menghapus
    // ------------------------------------------------------------------

    public function test_non_admin_cannot_write_data(): void
    {
        $writes = [
            ['post', route('students.store'), ['name' => 'Penyusup', 'class_id' => $this->class->id, 'status' => 'active']],
            ['put', route('students.update', $this->studentRecord), ['name' => 'Diubah', 'class_id' => $this->class->id, 'status' => 'active']],
            ['delete', route('students.destroy', $this->studentRecord), []],

            ['post', route('teachers.store'), ['name' => 'Penyusup', 'subject_id' => $this->subject->id]],
            ['put', route('teachers.update', $this->teacherRecord), ['name' => 'Diubah', 'subject_id' => $this->subject->id]],
            ['delete', route('teachers.destroy', $this->teacherRecord), []],

            ['post', route('classes.store'), ['name' => 'Kelas Penyusup']],
            ['put', route('classes.update', $this->class), ['name' => 'Diubah']],
            ['delete', route('classes.destroy', $this->class), []],

            ['post', route('subjects.store'), ['code' => 'XXX', 'name' => 'Mapel Penyusup']],
            ['put', route('subjects.update', $this->subject), ['code' => 'MTK', 'name' => 'Diubah']],
            ['delete', route('subjects.destroy', $this->subject), []],

            ['post', route('users.store'), [
                'name' => 'Penyusup', 'email' => 'penyusup@uji.test', 'role' => 'admin',
                'password' => 'password', 'password_confirmation' => 'password',
            ]],
            ['put', route('users.update', $this->teacherUser), ['name' => 'Diubah', 'email' => 'guru@uji.test', 'role' => 'admin']],
            ['delete', route('users.destroy', $this->teacherUser), []],
        ];

        foreach ([$this->teacherUser, $this->studentUser] as $user) {
            foreach ($writes as [$method, $url, $data]) {
                $response = $this->actingAs($user)->{$method}($url, $data);

                $this->assertSame(
                    403,
                    $response->getStatusCode(),
                    "Role {$user->role} tidak boleh {$method} {$url}"
                );
            }
        }

        // Tidak ada data yang berubah
        $this->assertDatabaseMissing('tbl_students', ['name' => 'Penyusup']);
        $this->assertDatabaseMissing('tbl_teachers', ['name' => 'Penyusup']);
        $this->assertDatabaseMissing('tbl_classes', ['name' => 'Kelas Penyusup']);
        $this->assertDatabaseMissing('tbl_subjects', ['code' => 'XXX']);
        $this->assertDatabaseMissing('tbl_users', ['email' => 'penyusup@uji.test']);

        $this->assertDatabaseHas('tbl_students', ['id' => $this->studentRecord->id, 'name' => 'Siswa Uji', 'archived' => 0]);
        $this->assertDatabaseHas('tbl_teachers', ['id' => $this->teacherRecord->id, 'name' => 'Guru Uji', 'archived' => 0]);
        $this->assertDatabaseHas('tbl_classes', ['id' => $this->class->id, 'name' => '10 IPA 1', 'archived' => 0]);
        $this->assertDatabaseHas('tbl_subjects', ['id' => $this->subject->id, 'name' => 'Matematika', 'archived' => 0]);
        $this->assertDatabaseHas('tbl_users', ['id' => $this->teacherUser->id, 'role' => 'teacher', 'archived' => 0]);
    }

    // ------------------------------------------------------------------
    // Isi dashboard dan menu sesuai role
    // ------------------------------------------------------------------

    public function test_dashboard_and_menu_follow_the_role(): void
    {
        $this->actingAs($this->admin)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Pengguna (Users)')
            ->assertSee('Data Pengguna')
            ->assertSee('Data Siswa');

        $this->actingAs($this->teacherUser)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Siswa (Students)')
            ->assertSee('Data Siswa')
            ->assertDontSee('Pengguna (Users)')
            ->assertDontSee('Data Pengguna');

        $this->actingAs($this->studentUser)->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Data Siswa')
            ->assertDontSee('Data Guru')
            ->assertDontSee('Data Pengguna');
    }

    public function test_student_only_sees_own_data(): void
    {
        Student::create([
            'class_id' => $this->class->id,
            'student_code' => 'STD-002',
            'name' => 'Murid Lain',
            'status' => 'active',
        ]);

        $this->actingAs($this->studentUser)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Siswa Uji')
            ->assertDontSee('Murid Lain');

        // Admin melihat semuanya
        $this->actingAs($this->admin)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Murid Lain');
    }

    // ------------------------------------------------------------------
    // Admin: fungsi CRUD dan aturan data
    // ------------------------------------------------------------------

    public function test_admin_can_create_and_soft_delete_student(): void
    {
        $this->actingAs($this->admin)->post(route('students.store'), [
            'name' => 'Rina Putri',
            'class_id' => $this->class->id,
            'status' => 'active',
            'birth_date' => '2010-05-20',
        ])->assertRedirect(route('students.index'));

        $this->assertDatabaseHas('tbl_students', ['name' => 'Rina Putri', 'class_id' => $this->class->id, 'archived' => 0]);

        $student = Student::where('name', 'Rina Putri')->firstOrFail();

        $this->actingAs($this->admin)->delete(route('students.destroy', $student))
            ->assertRedirect(route('students.index'));

        // Soft delete: baris tetap ada, hanya diarsipkan
        $this->assertDatabaseHas('tbl_students', ['id' => $student->id, 'archived' => 1]);
    }

    public function test_student_form_validation_rejects_empty_name(): void
    {
        $this->actingAs($this->admin)
            ->post(route('students.store'), ['class_id' => $this->class->id, 'status' => 'active'])
            ->assertSessionHasErrors('name');
    }

    public function test_class_and_subject_with_active_children_cannot_be_deleted(): void
    {
        // Kelas dengan siswa aktif dan mapel dengan guru aktif ditolak
        $this->actingAs($this->admin)->delete(route('classes.destroy', $this->class))
            ->assertRedirect(route('classes.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('tbl_classes', ['id' => $this->class->id, 'archived' => 0]);

        $this->actingAs($this->admin)->delete(route('subjects.destroy', $this->subject))
            ->assertRedirect(route('subjects.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('tbl_subjects', ['id' => $this->subject->id, 'archived' => 0]);

        // Yang kosong boleh dihapus
        $emptyClass = SchoolClass::create(['name' => 'Kelas Kosong']);
        $emptySubject = Subject::create(['code' => 'KSG', 'name' => 'Mapel Kosong']);

        $this->actingAs($this->admin)->delete(route('classes.destroy', $emptyClass))
            ->assertSessionHas('success');
        $this->actingAs($this->admin)->delete(route('subjects.destroy', $emptySubject))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tbl_classes', ['id' => $emptyClass->id, 'archived' => 1]);
        $this->assertDatabaseHas('tbl_subjects', ['id' => $emptySubject->id, 'archived' => 1]);
    }

    public function test_admin_cannot_delete_or_demote_own_account(): void
    {
        $this->actingAs($this->admin)->delete(route('users.destroy', $this->admin))
            ->assertRedirect(route('users.index'))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('tbl_users', ['id' => $this->admin->id, 'archived' => 0]);

        $this->actingAs($this->admin)->put(route('users.update', $this->admin), [
            'name' => 'Admin Uji',
            'email' => 'admin@uji.test',
            'role' => 'teacher',
        ])->assertRedirect(route('users.index'));

        $this->assertSame('admin', $this->admin->fresh()->role);
    }

    // ------------------------------------------------------------------
    // Login, logout, dan akun diarsipkan
    // ------------------------------------------------------------------

    public function test_every_role_can_login_and_logout(): void
    {
        foreach ([$this->admin, $this->teacherUser, $this->studentUser] as $user) {
            $this->post('/login', ['email' => $user->email, 'password' => 'password'])
                ->assertRedirect(route('dashboard', absolute: false));
            $this->assertAuthenticatedAs($user);

            $this->post('/logout');
            $this->assertGuest();
        }
    }

    public function test_wrong_password_is_rejected(): void
    {
        $this->post('/login', ['email' => $this->admin->email, 'password' => 'salah-total'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_archived_user_cannot_login(): void
    {
        $this->teacherUser->update(['archived' => 1]);

        $this->post('/login', ['email' => $this->teacherUser->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_archived_user_with_active_session_is_logged_out(): void
    {
        // Sedang login dan bisa membuka dashboard
        $this->actingAs($this->teacherUser)->get(route('dashboard'))->assertOk();

        // Admin mengarsipkan akunnya saat sesi masih berjalan
        $this->teacherUser->update(['archived' => 1]);

        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    // ------------------------------------------------------------------
    // Fitur yang sengaja dihapus dan slug pada URL
    // ------------------------------------------------------------------

    public function test_removed_features_are_not_reachable(): void
    {
        $this->get('/register')->assertNotFound();
        $this->get('/forgot-password')->assertNotFound();

        // Hapus akun sendiri lewat Profil sudah tidak ada (DELETE /profile)
        $this->actingAs($this->admin)->delete('/profile')->assertStatus(405);
    }

    public function test_urls_use_slug_not_numeric_id(): void
    {
        $url = route('students.edit', $this->studentRecord);

        $this->assertStringContainsString('/students/siswa-uji-', $url);
        $this->actingAs($this->admin)->get($url)->assertOk();

        $this->actingAs($this->admin)
            ->get('/students/'.$this->studentRecord->id.'/edit')
            ->assertNotFound();
    }
}
