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
        Schema::table('MstPeserta', function (Blueprint $table) {
            // Add created_at and updated_at columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('MstPeserta', function (Blueprint $table) {
            // Drop created_at and updated_at columns if the migration is rolled back
            $table->dropTimestamps();
        });
    }
};
