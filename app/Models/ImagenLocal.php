<?php

// app/Models/ImagenLocal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenLocal extends Model
{
    use HasFactory;
    protected $table = 'imagenes_locales';

    protected $fillable = [
        'local_id',
        'url',
    ];

    public function local()
    {
        return $this->belongsTo(Local::class,'local_id');
    }
}
