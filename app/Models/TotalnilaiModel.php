<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalnilaiModel extends Model
{
    use HasFactory;
    protected $table = 'tb_totalnilai';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_alternatif',
        'id_kriteria',
        'nilai',
    ];

    public function alternatif()
    {
        return $this->belongsTo('App\Models\AlternatifModel','id_alternatif');
    }

    public function kriteria()
    {
        return $this->belongsTo('App\Models\KriteriaModel','id_kriteria');
    }
}
