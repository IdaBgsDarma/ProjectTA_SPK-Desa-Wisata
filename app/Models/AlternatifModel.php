<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlternatifModel extends Model
{
    use HasFactory;
    protected $table = 'tb_alternatif';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'status'
    ];
}
