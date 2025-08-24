<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CripsModel extends Model
{
    use HasFactory;
    protected $table = 'tb_crips';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_kriteria',
        'nama',
        'bobot',
        'status',
    ];

    public function kriteria()
    {
        return $this->belongsTo('App\Models\KriteriaModel','id_kriteria');
    }
}
