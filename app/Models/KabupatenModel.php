<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class KabupatenModel extends Model
{
    use HasFactory, Sluggable;
    protected $table = 'tb_kabupaten';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama',
        'slug',
        'foto',
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
}
