<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstKU extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'MstKU'; // Explicitly set the table name

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'IDKU'; // As per your screenshot

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true; // IDKU is auto_increment

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int'; // IDKU is int

    /**
     * Indicates if the model should be timestamped.
     *
     * Your MstKU table does not appear to have 'created_at' and 'updated_at'.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'KU',
        'TGLACUAN',
        'LAHIRMULAI',
        'LAHIRSAMPAI',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'TGLACUAN' => 'date', // Cast to Carbon date instance
        'LAHIRMULAI' => 'date',
        'LAHIRSAMPAI' => 'date',
    ];
}
