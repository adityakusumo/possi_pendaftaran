<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecialUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime', // Cast to Carbon instance
    ];
}
