<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Atlet extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name (e.g., 'atlets')
    protected $table = 'Atlet';

    // Specify the primary key if it's not 'id'
    protected $primaryKey = 'IDATLET';

    // // Disable auto-incrementing for string primary keys
    // public $incrementing = false;

    // // Set the primary key type to string
    // protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'NONIAS',
        'NAMACLUB',
        'JENISDOM',
        'NAMAKOTADOM',
        'NAMAPROPDOM',
        'NAMAATLET',
        'GENDER',
        'SP',
        'ASAL',
        'TGLLAHIR',
        'KU',
        'EXPIRED',
        // 'created_by',
        // 'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'TGLLAHIR' => 'date', // Cast TGLLAHIR to a Carbon instance
        'EXPIRED' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function (Atlet $atlet) {
            $atlet->created_by = Auth::id();
            $atlet->updated_by = Auth::id(); // Also set updated_by on creation
        });

        static::updating(function (Atlet $atlet) {
            $atlet->updated_by = Auth::id();
        });
    }
}
