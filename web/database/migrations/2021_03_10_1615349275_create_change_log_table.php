<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChangeLogTable extends Migration
{
    public function up()
    {
        Schema::create('change_log', function (Blueprint $table) {

        $table->id();
		$table->string('mat_aluno',50)->nullable();
		$table->string('mat_monitor',50)->nullable();
		$table->string('mensagem', 255)->nullable();
        $table->string('reserva_id',50)->nullable();
		$table->integer('baia_id')->nullable();
		$table->integer('equipamento_id')->nullable();
        $table->foreignId('evento_id')->constrained('tipo_evento');
        $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('change_log');
    }
}