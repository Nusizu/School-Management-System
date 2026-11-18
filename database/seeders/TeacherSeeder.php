<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $subjectIds = Subject::pluck('id', 'code');
        $teacherUserId = User::where('email', 'teacher@school.test')->value('id');

        // [nomor induk, nama, kode mapel, telepon, alamat]
        $teachers = [
            ['197706172001041001', 'Ahmad Fauzi, S.Pd.', 'MTK', '087898797309', 'Jl. Pemuda No. 15, RT 05/RW 02, Krobokan, Kota Semarang'],
            ['197411162000042002', 'Dewi Lestari, S.Pd.', 'BIN', '087845568151', 'Jl. Imam Bonjol No. 37, RT 08/RW 03, Banyumanik, Kota Semarang'],
            ['199205012018062003', 'Rina Wulandari, S.Pd.', 'BIG', '089561698716', 'Jl. Mawar No. 51, RT 09/RW 05, Sendangmulyo, Kota Semarang'],
            ['198710162011101004', 'Hendra Wijaya, S.Si.', 'FIS', '082220819672', 'Jl. Mawar No. 30, RT 02/RW 11, Srondol, Kota Semarang'],
            ['198811162014092005', 'Siti Nurhaliza, S.Si.', 'KIM', '087848876634', 'Jl. Veteran No. 2, RT 01/RW 03, Banyumanik, Kota Semarang'],
            ['199308262020061006', 'Agus Prasetyo, S.Pd.', 'BIO', '082247787680', 'Jl. Imam Bonjol No. 41, RT 08/RW 02, Krobokan, Kota Semarang'],
            ['199005152015071007', 'Bambang Hartono, S.Pd.', 'SEJ', '089514751362', 'Jl. Melati No. 2, RT 02/RW 01, Krobokan, Kota Semarang'],
            ['199209272016102008', 'Lilis Suryani, S.Pd.', 'GEO', '082231908452', 'Jl. Diponegoro No. 41, RT 08/RW 12, Srondol, Kota Semarang'],
            ['197809082005101009', 'Yoga Pratama, S.E.', 'EKO', '082198416229', 'Jl. Imam Bonjol No. 65, RT 07/RW 07, Tembalang, Kota Semarang'],
            ['197809262003072010', 'Nur Aini, S.Sos.', 'SOS', '081216105500', 'Jl. Mawar No. 42, RT 05/RW 08, Peterongan, Kota Semarang'],
            ['197708152002032011', 'Fitriani, S.Pd.', 'PKN', '082269579318', 'Jl. Sriwijaya No. 5, RT 04/RW 11, Kembangsari, Kota Semarang'],
            ['197511181999081012', 'Eko Susilo, S.Pd.', 'PJK', '082270283822', 'Jl. Ahmad Yani No. 50, RT 05/RW 07, Pedurungan, Kota Semarang'],
            ['198702132013032013', 'Maya Anggraini, S.Sn.', 'SBD', '085724776472', 'Jl. Dr. Cipto No. 67, RT 08/RW 08, Pandansari, Kota Semarang'],
            ['199009262016021014', 'Dian Kurniawan, S.Kom.', 'INF', '082230175682', 'Jl. Diponegoro No. 46, RT 03/RW 06, Pedurungan, Kota Semarang'],
            ['199209072016101015', 'Rudi Hermawan, S.Pd.', 'MTK', '085289394948', 'Jl. Merdeka No. 13, RT 05/RW 02, Karangayu, Kota Semarang'],
            ['197902222003062016', 'Annisa Rahmawati, M.Pd.', 'BIG', '082118549145', 'Jl. Ahmad Yani No. 34, RT 05/RW 02, Sekayu, Kota Semarang'],
        ];

        foreach ($teachers as $index => [$employeeNumber, $name, $subjectCode, $phone, $address]) {
            Teacher::updateOrCreate(
                ['employee_number' => $employeeNumber],
                [
                    'name' => $name,
                    'subject_id' => $subjectIds[$subjectCode],
                    'phone' => $phone,
                    'address' => $address,
                    // Guru pertama ditautkan ke akun demo teacher@school.test
                    'user_id' => $index === 0 ? $teacherUserId : null,
                ]
            );
        }
    }
}
