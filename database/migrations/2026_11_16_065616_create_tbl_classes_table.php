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
    Schema::create('tbl_classes', function (Blueprint $table) {
        $table->id();
        $table->string('name', 50);
        $table->text('description')->nullable();
        $table->tinyInteger('archived')->default(0);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('tbl_classes');
}
};
