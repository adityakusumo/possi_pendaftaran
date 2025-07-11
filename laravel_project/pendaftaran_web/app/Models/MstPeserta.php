<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstPeserta extends Model
{
    use HasFactory;

    // Assuming your table name is 'mst_peserta' (Laravel's default plural snake_case)
    // If your table name is exactly 'MstPeserta', uncomment and set:
    protected $table = 'MstPeserta';

    // Primary key customisation
    protected $primaryKey = 'IDPESERTA';
    public $incrementing = true; // IDPESERTA is auto-incrementing
    protected $keyType = 'int'; // IDPESERTA is an integer

    protected $fillable = [
        'ASAL',
        'NAMACLUB',
        'JENISDOM',
        'NAMAKOTADOM',
        'NAMAPROPDOM',
        'NAMANEGDOM',
        'CONTACTPERSON',
        'TELPON',
        'OFFICIAL', // Assuming this is 'jumlah official'
        'KETERANGAN', // Should be null per your spec
        'email', // User's email
    ];

    // No need for casts unless you have dates/JSON etc.
}
