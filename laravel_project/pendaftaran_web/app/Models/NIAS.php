<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NIAS extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'NIAS'; // Explicitly set the table name to 'NIAS'

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ID'; // Set the primary key to 'ID'

    /**
     * Indicates if the model should be timestamped.
     *
     * If your 'NIAS' table does NOT have 'created_at' and 'updated_at' columns,
     * set this to false. If it does, remove this line or set to true.
     * Based on your screenshot, it seems you have custom timestamp columns (TGLLAHIR, EXPIRED)
     * so it's safer to set this to false.
     *
     * @var bool
     */
    // public $timestamps = false; // Set to false if you don't use Laravel's default timestamps

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'KDPPROP',
        'NAMAPROP',
        'KDJENIS',
        'KDKOTA',
        'NAMAKOTA',
        'KDCLUB',
        'NAMACLUB',
        'GENDER',
        'NAMA',
        'TPTLAHIR', // Assuming this is 'Tempat Lahir'
        'TGLLAHIR', // Timestamp type, but not Laravel's default
        'STATUS',
        'NONIAS', // Assuming this is the NIAS number itself
        'LASTMUTASI',
        'MUTASI',
        'EXPIRED', // Timestamp type, but not Laravel's default
        'KDJENISDOM',
        'KDKOTADOM',
        'NAMAKOTADOM',
        'KDPROPDOM',
        'NAMAPROPDOM',
        'NIK',
        'EMAIL',
        'NOKARTUNAS',
        // 'ID' is auto-incrementing, so it's not typically in $fillable
    ];

    /**
     * The attributes that should be cast.
     *
     * This is useful for date/time columns to ensure they are Carbon instances.
     * Adjust 'datetime' if they are just 'date' or other formats.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'TGLLAHIR' => 'date', // Cast to Carbon instance
        'EXPIRED' => 'date',   // Cast to Carbon instance
        'created_at' => 'datetime', // Optional: Add these if you want them as Carbon instances
        'updated_at' => 'datetime', // Optional: Add these if you want them as Carbon instances
    ];

    // You can add relationships, custom methods, etc., here later if needed.
}
