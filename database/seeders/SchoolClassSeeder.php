<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            ['10 IPA 1', 'Peminatan IPA, Ruang 101'],
            ['10 IPA 2', 'Peminatan IPA, Ruang 102'],
            ['10 IPS 1', 'Peminatan IPS, Ruang 103'],
            ['11 IPA 1', 'Peminatan IPA, Ruang 201'],
            ['11 IPA 2', 'Peminatan IPA, Ruang 202'],
            ['11 IPS 1', 'Peminatan IPS, Ruang 203'],
            ['11 IPS 2', 'Peminatan IPS, Ruang 204'],
            ['12 IPA 1', 'Peminatan IPA, Ruang 301'],
            ['12 IPA 2', 'Peminatan IPA, Ruang 302'],
            ['12 IPA 3', 'Peminatan IPA, Ruang 303'],
            ['12 IPS 1', 'Peminatan IPS, Ruang 304'],
            ['12 IPS 2', 'Peminatan IPS, Ruang 305'],
        ];

        foreach ($classes as [$name, $description]) {
            SchoolClass::updateOrCreate(['name' => $name], ['description' => $description]);
        }
    }
}
