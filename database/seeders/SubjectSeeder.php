<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['MTK', 'Matematika', 'Aljabar, geometri, trigonometri, dan statistika'],
            ['BIN', 'Bahasa Indonesia', 'Membaca, menulis, dan apresiasi sastra Indonesia'],
            ['BIG', 'Bahasa Inggris', 'Kemampuan berbahasa Inggris untuk komunikasi akademik'],
            ['FIS', 'Fisika', 'Mekanika, listrik, gelombang, dan fisika modern'],
            ['KIM', 'Kimia', 'Struktur materi, reaksi kimia, dan stoikiometri'],
            ['BIO', 'Biologi', 'Sel, genetika, ekosistem, dan evolusi'],
            ['SEJ', 'Sejarah', 'Sejarah Indonesia dan sejarah dunia'],
            ['GEO', 'Geografi', 'Geografi fisik, sosial, dan pemetaan'],
            ['EKO', 'Ekonomi', 'Ekonomi mikro, makro, dan akuntansi dasar'],
            ['SOS', 'Sosiologi', 'Interaksi sosial, kelompok, dan perubahan sosial'],
            ['PKN', 'Pendidikan Pancasila dan Kewarganegaraan', 'Pancasila, konstitusi, dan kewarganegaraan'],
            ['PJK', 'Pendidikan Jasmani, Olahraga, dan Kesehatan', 'Kebugaran jasmani, olahraga, dan kesehatan'],
            ['SBD', 'Seni Budaya', 'Seni rupa, musik, tari, dan teater'],
            ['INF', 'Informatika', 'Berpikir komputasional, pemrograman, dan literasi digital'],
        ];

        foreach ($subjects as [$code, $name, $description]) {
            Subject::updateOrCreate(['code' => $code], ['name' => $name, 'description' => $description]);
        }
    }
}
