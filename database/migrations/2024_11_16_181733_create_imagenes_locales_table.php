<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_imagenes_locales_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagenesLocalesTable extends Migration
{
    public function up()
    {
        Schema::create('imagenes_locales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('local_id')->constrained('locales'); // Relación con la tabla locales
            $table->string('url'); // Para almacenar la ruta de la imagen
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('imagenes_locales');
    }
}

