<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTipoEventoTable extends Migration
{
    public function up()
    {
        Schema::create('tipo_evento', function (Blueprint $table) {

		    $table->id();
            $table->string('descricao')->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('tipo_evento');
    }
}