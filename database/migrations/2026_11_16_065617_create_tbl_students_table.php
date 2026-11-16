<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('tbl_students', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->unique()->constrained('tbl_users')->nullOnDelete();
        $table->foreignId('class_id')->constrained('tbl_classes');
        $table->string('student_code', 20)->unique();
        $table->string('name', 100);
        $table->date('birth_date')->nullable();
        $table->text('address')->nullable();
        $table->string('phone', 20)->nullable();
        $table->string('img_url')->nullable();
        $table->string('status', 20)->default('active'); // active, transferred
        $table->tinyInteger('archived')->default(0);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('tbl_students');
}
};
