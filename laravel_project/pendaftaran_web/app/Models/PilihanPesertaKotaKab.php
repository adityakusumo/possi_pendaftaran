<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilihanPesertaKotaKab extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel (plural dari nama model)
    protected $table = 'PilihanPesertaKotaKab';

    // Tentukan primary key jika tidak 'id'
    protected $primaryKey = 'IDPILIHAN';

    // Tentukan kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'NAMACLUB',
        'JENIS',
        'NAMAKOTA',
        'NAMAPROPINSI',
        'NAMANEGARA',
    ];
}
