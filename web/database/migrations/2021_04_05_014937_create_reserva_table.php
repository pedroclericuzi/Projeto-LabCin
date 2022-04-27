<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reserva', function (Blueprint $table) {
            $table->id();
			$table->string('cpf',20)->nullable();
			$table->string('justificativa',500)->nullable();
			$table->string('observacoes',500)->nullable();
			$table->date('data')->nullable();
            $table->time('hora')->nullable();
			$table->foreignId('baia_id')->constrained('baia');
			$table->foreignId('status_id')->constrained('status_reserva');
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserva');
    }
}
