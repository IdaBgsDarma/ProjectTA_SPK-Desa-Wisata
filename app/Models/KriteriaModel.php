<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KriteriaModel extends Model
{
    use HasFactory;
    protected $table = 'tb_kriteria';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'bobot',
        'cost_benefit',
        'status'
    ];
}
