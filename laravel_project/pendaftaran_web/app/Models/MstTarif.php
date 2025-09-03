<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstTarif extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'MstTarif';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'IDTARIF';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'NOURUT',
        'ASALPESERTA',
        'NAMAPROPINSI',
        'NAMANEGARA',
        'NOMOR',
        'KOTARIF',
        'KETERANGAN',
        'RPTARIF',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
