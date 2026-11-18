<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $classIds = SchoolClass::pluck('id', 'name');
        $studentUserId = User::where('email', 'student@school.test')->value('id');

        // [kode, nama, kelas, status, tanggal lahir, telepon, alamat]
        $students = [
            ['STD-001', 'Budi Santoso', '10 IPA 1', 'active', '2010-03-14', '085717883655', 'Jl. Kartini No. 52, RT 03/RW 01, Tegalsari, Kota Semarang'],
            ['STD-002', 'Siti Aminah', '11 IPS 2', 'active', '2009-07-02', '085225996775', 'Jl. Diponegoro No. 5, RT 04/RW 11, Pandansari, Kota Semarang'],
            ['STD-003', 'Andi Saputra', '12 IPA 3', 'transferred', '2008-11-21', '085250378495', 'Jl. Pemuda No. 72, RT 04/RW 09, Tembalang, Kota Semarang'],
            ['STD-004', 'Bagas Ardiansyah', '12 IPA 2', 'active', '2008-12-28', '081212537392', 'Jl. Imam Bonjol No. 11, RT 07/RW 12, Pedurungan, Kota Semarang'],
            ['STD-005', 'Tiara Anggraini', '12 IPA 1', 'active', '2008-01-30', '082110460288', 'Jl. Pandanaran No. 61, RT 06/RW 06, Peterongan, Kota Semarang'],
            ['STD-006', 'Bagas Maulana', '12 IPS 1', 'active', '2009-03-01', '085797475407', 'Jl. Merdeka No. 3, RT 08/RW 06, Tegalsari, Kota Semarang'],
            ['STD-007', 'Arief Susanto', '11 IPS 2', 'active', '2009-08-13', '089535486439', 'Jl. Kartini No. 53, RT 02/RW 12, Srondol, Kota Semarang'],
            ['STD-008', 'Rani Handayani', '11 IPA 1', 'active', '2009-01-30', '085279188957', 'Jl. Dr. Cipto No. 85, RT 06/RW 06, Kembangsari, Kota Semarang'],
            ['STD-009', 'Qonita Kusuma', '11 IPA 1', 'active', '2010-02-11', '081382488619', 'Jl. Gajah Mada No. 4, RT 08/RW 04, Karangturi, Kota Semarang'],
            ['STD-010', 'Bella Wulandari', '12 IPS 2', 'active', '2008-08-21', '087888283472', 'Jl. Dr. Cipto No. 47, RT 07/RW 09, Tlogosari, Kota Semarang'],
            ['STD-011', 'Galih Fauzi', '12 IPA 2', 'active', '2009-05-05', '082229822290', 'Jl. Kartini No. 36, RT 05/RW 03, Kembangsari, Kota Semarang'],
            ['STD-012', 'Hafiz Hidayat', '12 IPA 2', 'active', '2008-04-01', '085285272202', 'Jl. Merdeka No. 53, RT 08/RW 09, Sekayu, Kota Semarang'],
            ['STD-013', 'Ma\'ruf Hidayat', '12 IPA 3', 'active', '2009-06-10', '085238581762', 'Jl. Dr. Cipto No. 19, RT 09/RW 01, Krobokan, Kota Semarang'],
            ['STD-014', 'Fitri Kusuma', '11 IPS 1', 'active', '2010-06-17', '081201594502', 'Jl. Gajah Mada No. 12, RT 07/RW 11, Karangayu, Kota Semarang'],
            ['STD-015', 'Fitri Rahmawati', '10 IPA 2', 'active', '2010-01-12', '082223373528', 'Jl. Kenanga No. 93, RT 07/RW 08, Sendangmulyo, Kota Semarang'],
            ['STD-016', 'Anisa Suryani', '12 IPA 3', 'active', '2008-02-29', '085734789713', 'Jl. Gajah Mada No. 15, RT 02/RW 06, Srondol, Kota Semarang'],
            ['STD-017', 'Vina Handayani', '11 IPS 1', 'active', '2009-06-04', '082112363608', 'Jl. Merdeka No. 83, RT 05/RW 05, Srondol, Kota Semarang'],
            ['STD-018', 'Dhea Utami', '10 IPA 2', 'active', '2011-01-04', '085760787457', 'Jl. Merdeka No. 14, RT 03/RW 09, Karangturi, Kota Semarang'],
            ['STD-019', 'Anisa Handayani', '12 IPS 1', 'active', '2008-10-09', '085776990854', 'Jl. Pemuda No. 60, RT 08/RW 06, Pedurungan, Kota Semarang'],
            ['STD-020', 'Naufal Maulana', '11 IPA 1', 'active', '2010-01-03', '081344019018', 'Jl. Melati No. 73, RT 02/RW 07, Tembalang, Kota Semarang'],
            ['STD-021', 'Taufik Fauzi', '10 IPS 1', 'transferred', '2011-05-01', '089597802520', 'Jl. Sudirman No. 51, RT 02/RW 03, Pedurungan, Kota Semarang'],
            ['STD-022', 'Vina Azizah', '11 IPA 2', 'active', '2009-11-16', '085200198152', 'Jl. Imam Bonjol No. 61, RT 04/RW 06, Srondol, Kota Semarang'],
            ['STD-023', 'Alif Susanto', '12 IPS 1', 'active', '2008-06-06', '089536385607', 'Jl. Pandanaran No. 77, RT 03/RW 08, Karangturi, Kota Semarang'],
            ['STD-024', 'Andi Maulana', '12 IPA 1', 'active', '2009-06-19', '081397967807', 'Jl. Gajah Mada No. 50, RT 01/RW 09, Krobokan, Kota Semarang'],
            ['STD-025', 'Bella Rahmawati', '12 IPS 1', 'active', '2008-06-25', '082143854690', 'Jl. Majapahit No. 78, RT 09/RW 03, Pedurungan, Kota Semarang'],
            ['STD-026', 'Ilham Fauzi', '12 IPA 1', 'active', '2008-09-28', '081395391827', 'Jl. Sriwijaya No. 15, RT 02/RW 07, Karangayu, Kota Semarang'],
            ['STD-027', 'Zidan Wijaya', '10 IPS 1', 'active', '2010-01-19', '081233042845', 'Jl. Pemuda No. 33, RT 03/RW 01, Karangturi, Kota Semarang'],
            ['STD-028', 'Dhea Ningsih', '10 IPS 1', 'active', '2010-11-11', '081378057803', 'Jl. Mawar No. 7, RT 05/RW 02, Kembangsari, Kota Semarang'],
            ['STD-029', 'Kirana Utami', '10 IPA 2', 'active', '2011-06-13', '081343049074', 'Jl. Pemuda No. 90, RT 09/RW 03, Pedurungan, Kota Semarang'],
            ['STD-030', 'Vina Wulandari', '11 IPS 2', 'active', '2010-06-05', '082270853867', 'Jl. Pemuda No. 32, RT 01/RW 01, Karangayu, Kota Semarang'],
            ['STD-031', 'Bagas Syahputra', '12 IPS 2', 'active', '2008-12-07', '087881792232', 'Jl. Gajah Mada No. 65, RT 06/RW 07, Tegalsari, Kota Semarang'],
            ['STD-032', 'Dinda Rahmawati', '11 IPS 2', 'active', '2009-07-14', '082171051298', 'Jl. Pandanaran No. 61, RT 03/RW 11, Tembalang, Kota Semarang'],
            ['STD-033', 'Yusuf Ramadhan', '10 IPS 1', 'active', '2010-10-20', '085727812061', 'Jl. Mawar No. 81, RT 07/RW 11, Tlogosari, Kota Semarang'],
            ['STD-034', 'Reza Nugroho', '11 IPA 2', 'active', '2009-01-10', '085785190431', 'Jl. Sriwijaya No. 32, RT 05/RW 10, Banyumanik, Kota Semarang'],
            ['STD-035', 'Tiara Suryani', '12 IPA 3', 'active', '2009-03-08', '082148005279', 'Jl. Sudirman No. 32, RT 08/RW 03, Tlogosari, Kota Semarang'],
            ['STD-036', 'Kevin Fauzi', '11 IPS 1', 'active', '2009-06-24', '089511209842', 'Jl. Kenanga No. 33, RT 01/RW 08, Tlogosari, Kota Semarang'],
            ['STD-037', 'Siti Wulandari', '12 IPA 2', 'active', '2008-11-18', '085226079113', 'Jl. Dr. Cipto No. 57, RT 02/RW 07, Tegalsari, Kota Semarang'],
            ['STD-038', 'Rani Rahmawati', '10 IPS 1', 'active', '2010-04-30', '089574479674', 'Jl. Dr. Cipto No. 22, RT 02/RW 10, Pedurungan, Kota Semarang'],
            ['STD-039', 'Wulan Kusuma', '12 IPA 2', 'active', '2008-01-03', '081233463521', 'Jl. Pandanaran No. 50, RT 03/RW 10, Peterongan, Kota Semarang'],
            ['STD-040', 'Nabila Puspitasari', '11 IPS 1', 'active', '2009-11-06', '087839848864', 'Jl. Majapahit No. 58, RT 04/RW 03, Pedurungan, Kota Semarang'],
            ['STD-041', 'Bagas Setiawan', '10 IPA 1', 'active', '2010-05-10', '085209342692', 'Jl. Melati No. 49, RT 08/RW 11, Sekayu, Kota Semarang'],
            ['STD-042', 'Hafiz Wibowo', '11 IPS 2', 'active', '2009-04-20', '085209858463', 'Jl. Imam Bonjol No. 86, RT 09/RW 06, Sendangmulyo, Kota Semarang'],
            ['STD-043', 'Zahra Lestari', '11 IPA 2', 'active', '2010-01-26', '085768306866', 'Jl. Majapahit No. 32, RT 09/RW 10, Karangayu, Kota Semarang'],
            ['STD-044', 'Yusuf Wijaya', '11 IPA 2', 'transferred', '2009-12-23', '085701332796', 'Jl. Mawar No. 21, RT 07/RW 09, Karangturi, Kota Semarang'],
            ['STD-045', 'Wahyu Wijaya', '12 IPA 1', 'active', '2009-06-15', '085708111361', 'Jl. Melati No. 77, RT 06/RW 11, Pedurungan, Kota Semarang'],
            ['STD-046', 'Ilham Prasetyo', '12 IPA 1', 'active', '2008-12-19', '081363540115', 'Jl. Merdeka No. 59, RT 07/RW 02, Banyumanik, Kota Semarang'],
            ['STD-047', 'Kevin Pratama', '12 IPA 3', 'active', '2009-05-21', '081338759211', 'Jl. Majapahit No. 31, RT 03/RW 08, Krobokan, Kota Semarang'],
            ['STD-048', 'Citra Safitri', '12 IPS 2', 'active', '2008-08-19', '081386210761', 'Jl. Melati No. 64, RT 01/RW 08, Krobokan, Kota Semarang'],
            ['STD-049', 'Bayu Ardiansyah', '10 IPA 2', 'active', '2010-12-06', '085710626148', 'Jl. Pandanaran No. 55, RT 02/RW 06, Kembangsari, Kota Semarang'],
            ['STD-050', 'Zidan Wibowo', '11 IPA 2', 'active', '2010-02-17', '081229944613', 'Jl. Majapahit No. 97, RT 02/RW 07, Banyumanik, Kota Semarang'],
            ['STD-051', 'Naufal Prasetyo', '10 IPA 1', 'active', '2010-08-14', '081399456994', 'Jl. Pandanaran No. 95, RT 09/RW 03, Pedurungan, Kota Semarang'],
            ['STD-052', 'Intan Safitri', '11 IPA 1', 'active', '2009-12-25', '087840894874', 'Jl. Kenanga No. 95, RT 05/RW 04, Srondol, Kota Semarang'],
            ['STD-053', 'Aditya Kusuma', '10 IPA 2', 'active', '2011-01-26', '089525844419', 'Jl. Pandanaran No. 1, RT 04/RW 10, Tlogosari, Kota Semarang'],
            ['STD-054', 'Taufik Kusuma', '10 IPA 1', 'active', '2010-05-11', '085751566979', 'Jl. Sriwijaya No. 87, RT 02/RW 06, Sendangmulyo, Kota Semarang'],
            ['STD-055', 'Hafiz Wijaya', '10 IPA 1', 'active', '2010-11-24', '087879596505', 'Jl. Sriwijaya No. 95, RT 06/RW 05, Tembalang, Kota Semarang'],
            ['STD-056', 'Taufik Hakim', '12 IPS 2', 'active', '2008-05-28', '089505670904', 'Jl. Kartini No. 15, RT 06/RW 07, Tegalsari, Kota Semarang'],
            ['STD-057', 'Citra Permata', '11 IPA 1', 'active', '2009-12-06', '087844033013', 'Jl. Ahmad Yani No. 7, RT 06/RW 10, Pedurungan, Kota Semarang'],
            ['STD-058', 'Qonita Safitri', '12 IPS 1', 'active', '2008-05-06', '089519889042', 'Jl. Sudirman No. 54, RT 04/RW 02, Srondol, Kota Semarang'],
            ['STD-059', 'Daffa Prasetyo', '12 IPS 2', 'active', '2009-02-14', '081311960278', 'Jl. Diponegoro No. 97, RT 05/RW 07, Peterongan, Kota Semarang'],
            ['STD-060', 'Gilang Firmansyah', '11 IPS 1', 'active', '2010-02-24', '089530642482', 'Jl. Sriwijaya No. 92, RT 01/RW 02, Krobokan, Kota Semarang'],
        ];

        foreach ($students as $index => [$code, $name, $class, $status, $birthDate, $phone, $address]) {
            Student::updateOrCreate(
                ['student_code' => $code],
                [
                    'name' => $name,
                    'class_id' => $classIds[$class],
                    'status' => $status,
                    'birth_date' => $birthDate,
                    'phone' => $phone,
                    'address' => $address,
                    // Siswa pertama (Budi Santoso) ditautkan ke akun demo student@school.test
                    'user_id' => $index === 0 ? $studentUserId : null,
                ]
            );
        }
    }
}
