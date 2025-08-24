<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiModel extends Model
{
    use HasFactory;
    protected $table = 'tb_nilai';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_alternatif',
        'id_kriteria',
        'id_crips',
        'nilai',
    ];

    public function kriteria()
    {
        return $this->belongsTo('App\Models\KriteriaModel','id_kriteria');
    }

    public function alternatif()
    {
        return $this->belongsTo('App\Models\AlternatifModel','id_alternatif');
    }

    public function crips()
    {
        return $this->belongsTo('App\Models\CripsModel','id_crips');
    }
}
