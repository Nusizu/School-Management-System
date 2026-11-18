<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private array $tables = ['tbl_users', 'tbl_students', 'tbl_teachers', 'tbl_classes', 'tbl_subjects'];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('slug', 191)->nullable()->unique()->after('name');
            });

            // Isi slug untuk data yang sudah ada
            foreach (DB::table($table)->select('id', 'name')->get() as $row) {
                DB::table($table)->where('id', $row->id)->update([
                    'slug' => (Str::slug($row->name) ?: 'item').'-'.$row->id,
                ]);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropUnique(['slug']);
                $t->dropColumn('slug');
            });
        }
    }
};
