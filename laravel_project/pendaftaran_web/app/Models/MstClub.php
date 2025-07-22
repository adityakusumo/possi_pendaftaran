<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstClub extends Model
{
    use HasFactory;

    protected $table = 'MstClub';
    protected $primaryKey = 'IDCLUB'; // <-- Changed to IDCLUB
    // public $incrementing = false; // Set to false if IDCLUB is not auto-incrementing
    // protected $keyType = 'string'; // Set to 'string' if IDCLUB is a string/UUID, otherwise 'int'

    // If IDCLUB is an integer and auto-incrementing, you can remove the two lines above.
    // However, if it's explicitly generated or non-numeric, keep them.

    public $timestamps = false; // Set to false if your table doesn't have created_at/updated_at

    protected $fillable = [
        'KDPROP', 'NAMAPROP', 'KDJENIS', 'JENIS', 'KDKOTA', 'NAMAKOTA', 'KDCLUB', 'NAMACLUB'
        // Add all columns you intend to use
    ];
}
