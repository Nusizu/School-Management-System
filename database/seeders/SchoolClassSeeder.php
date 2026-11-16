<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['10 IPA 1', '10 IPA 2', '11 IPA 1', '11 IPS 2', '12 IPA 3', '12 IPS 1'] as $name) {
            SchoolClass::firstOrCreate(['name' => $name]);
        }
    }
}