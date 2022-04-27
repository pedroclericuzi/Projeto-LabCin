<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissaoTable extends Migration
{
    public function up()
    {
        Schema::create('permissao', function (Blueprint $table) {
            $table->id();
            $table->string('descricao')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('permissao');
    }
}