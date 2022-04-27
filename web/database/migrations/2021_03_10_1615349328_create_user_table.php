<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {

		$table->id();
		$table->string('matricula',20)->nullable();
		$table->string('nome',45)->nullable();
		$table->string('rfid',45)->nullable();
        $table->string('disciplina_monitor',100)->nullable();
        $table->foreignId('status_id')->constrained('status_user');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->rememberToken();
        $table->foreignId('permissao_id')->constrained('permissao');
        $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pessoa');
    }
}