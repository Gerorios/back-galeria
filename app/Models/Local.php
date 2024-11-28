<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    use HasFactory;
    protected $table = 'locales';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'direccion',
        'tamano',
        'imagen',
    ];

    public function imagenesLocales()
    {
        return $this->hasMany(ImagenLocal::class, 'local_id');
    }
}
