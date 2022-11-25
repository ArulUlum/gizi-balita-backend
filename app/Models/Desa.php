<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    use HasFactory;

    protected $table = 'data_desa';

    protected $fillable = [
        'name'
    ];

    public function posyandu() {
        return $this->hasMany(Posyandu::class, 'id_desa', 'id');
    }

    public function anak(){
        return $this->hasMany(Anak::class, 'id_desa', 'id');
    }
}
