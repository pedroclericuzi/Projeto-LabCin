<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipamentoTable extends Migration
{
    public function up()
    {
        Schema::create('equipamento', function (Blueprint $table) {
			$table->id();
			$table->string('uuid_tag')->nullable();
			$table->string('marca',100)->nullable();
			$table->string('modelo',100)->nullable();
			$table->string('estado_conserv',45)->nullable();
            $table->string('tombamento',100)->nullable();
			$table->foreignId('baia_id')->constrained('baia');
			$table->foreignId('tipo_id')->constrained('tipo_equipamento');
			$table->foreignId('status_id')->constrained('status_equipamento');
			$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipamento');
    }
}