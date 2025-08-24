<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class DesaModel extends Model
{
    use HasFactory, Sluggable;
    protected $table = 'tb_desa';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_kabupaten',
        'nama',
        'slug',
        'foto',
        'lokasi',
        'status'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama',
                'onUpdate' => true
            ]
        ];
    }

    public function kabupaten()
    {
        return $this->belongsTo('App\Models\KabupatenModel','id_kabupaten');
    }
}
