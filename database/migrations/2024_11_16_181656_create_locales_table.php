<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_locales_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalesTable extends Migration
{
    public function up()
    {
        Schema::create('locales', function (Blueprint $table) {
            $table->id(); // Crea una columna id autoincrementable
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->enum('estado', ['libre', 'ocupado']);
            $table->string('direccion')->nullable();
            $table->string('tamano')->nullable();
            $table->timestamps(); // Crea las columnas created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('locales');
    }
}
