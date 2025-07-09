<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kompetisi extends Model
{
    use HasFactory;

    protected $table = 'Kompetisi'; // Ensure this matches your table name
    protected $fillable = [
        'JNSKOMPETISI',
        'KETKOMPETISI',
    ];
}
