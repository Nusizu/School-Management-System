<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('tbl_teachers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->unique()->constrained('tbl_users')->nullOnDelete();
        $table->foreignId('subject_id')->constrained('tbl_subjects');
        $table->string('employee_number', 30)->nullable()->unique();
        $table->string('name', 100);
        $table->string('phone', 20)->nullable();
        $table->text('address')->nullable();
        $table->tinyInteger('archived')->default(0);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('tbl_teachers');
}
};
