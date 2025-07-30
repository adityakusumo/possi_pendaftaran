<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class A3 extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'A3'; // Specify the table name if it's not the plural form of the model name

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'IDA3P'; // Specify the primary key if it's not 'id'

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true; // Set to true if you have 'created_at' and 'updated_at' columns

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Based on your A3 table columns from the screenshot
        'GENDER',
        'KU',
        'NAMAATLET',
        'ASAL',
        'NAMACLUB',
        'JENISDOM',
        'NAMAKOTADOM',
        'NAMAPROPDOM',
        'MON50MM',
        'MON50SS',
        'MON50HS',
        'MON100MM',
        'MON100SS',
        'MON100HS',
        'MON200MM',
        'MON200SS',
        'MON200HS',
        'MON400MM',
        'MON400SS',
        'MON400HS',
        'MON800MM',
        'MON800SS',
        'MON800HS',
        'MON1500MM',
        'MON1500SS',
        'MON1500HS',
        'SUB50MM',
        'SUB50SS',
        'SUB50HS',
        'SUB100MM',
        'SUB100SS',
        'SUB100HS',
        'SUB200MM',
        'SUB200SS',
        'SUB200HS',
        'SUB400MM',
        'SUB400SS',
        'SUB400HS',
        'APN50MM',
        'APN50SS',
        'APN50HS',
        'IMM100MM',
        'IMM100SS',
        'IMM100HS',
        'IMM400MM',
        'IMM400SS',
        'IMM400HS',
        'IMM800MM',
        'IMM800SS',
        'IMM800HS',
        'ESTMON200MM',
        'ESTMON200SS',
        'ESTMON200HS',
        'ESTMON400MM',
        'ESTMON400SS',
        'ESTMON400HS',
        'ESTMON800MM',
        'ESTMON800SS',
        'ESTMON800HS',
        'ESTSUB200MM',
        'ESTSUB200SS',
        'ESTSUB200HS',
        'ESTSUB400MM',
        'ESTSUB400SS',
        'ESTSUB400HS',
        'ESTMONM200MM',
        'ESTMONM200SS',
        'ESTMONM200HS',
        'ESTMONM400MM',
        'ESTMONM400SS',
        'ESTMONM400HS',
        'ESTSUB200MM',
        'ESTSUB200SS',
        'ESTSUB200HS',
        'ESTSUBM200MM',
        'ESTSUBM200SS',
        'ESTSUBM200HS',
        'ESTSUBM400MM',
        'ESTSUBM400SS',
        'ESTSUBM400HS',
        'SP',
        'TGLLAHIR', // Assuming this is string/date representation
        'NOMOR', // Assuming this is string
        'GENDERMIX', // Assuming this is string
        'email',
        // 'created_at' and 'updated_at' are handled automatically by Laravel if $timestamps is true
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // Define casts for date, datetime, or json columns if needed
        'TGLLAHIR' => 'date', // Example if TGL_LAHIR should be a Carbon instance
    ];
}
