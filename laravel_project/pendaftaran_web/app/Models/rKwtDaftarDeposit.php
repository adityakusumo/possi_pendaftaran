<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rKwtDaftarDeposit extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rKwtDaftarDeposit';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'IDKWTTOT';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'IDKWTTOT',
        'NOURUT',
        'TGLLUNAS',
        'ASAL',
        'NAMACLUB',
        'JENISDOM',
        'NAMAKOTADOM',
        'NAMAPROPDOM',
        'NAMANEGDOM',
        'ALAMATCLUB',
        'NOKWT',
        'NOMOR',
        'KOTARIF',
        'RPTARIF',
        'JMLATLET',
        'JMLNOLOMBA',
        'RPTDAFTAR',
        'RPDEPOSIT',
        'RPTDAFTDEPO',
        'RPPLAIN',
        'RPTOTAL',
        'TANDATANGAN',
        'email',
    ];

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
}
