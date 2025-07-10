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
        Schema::create('PilihanPesertaKotaKab', function (Blueprint $table) {
            $table->increments('IDPILIHAN'); // Primary Key, auto-incrementing INT(11)
            $table->string('NAMACLUB', 30)->nullable(false); // VARCHAR(30), NOT NULL
            $table->string('JENIS', 30)->nullable(false);    // VARCHAR(30), NOT NULL
            $table->string('NAMAKOTA', 30)->nullable(false); // VARCHAR(30), NOT NULL
            $table->string('NAMAPROPINSI', 30)->nullable(false); // VARCHAR(30), NOT NULL
            $table->string('NAMANEGARA', 30)->nullable(false)->default('INDONESIA'); // VARCHAR(30), NOT NULL, default 'INDONESIA'
            $table->timestamps(); // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('PilihanPesertaKotaKab');
    }
};
