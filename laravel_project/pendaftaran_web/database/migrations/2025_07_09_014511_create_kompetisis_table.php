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
        Schema::create('Kompetisi', function (Blueprint $table) {
            $table->id();
            // JNSKOMPETISI will store 'K', 'C', 'P'
            // We'll assume for competition settings, there's typically one active setting.
            // If you always want only one 'current' setting, you can use unique() here
            // or manage it in the controller by always updating a record with a fixed ID (e.g., 1).
            $table->string('JNSKOMPETISI', 1)->nullable(); // Can be 'K', 'C', 'P'
            $table->string('KETKOMPETISI')->nullable(); // Can be 'ANTAR KOTA', 'ANTAR CLUB', 'ANTAR PROVINSI'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Kompetisi');
    }
};
