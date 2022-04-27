<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusBaiaTable extends Migration
{
    public function up()
    {
        Schema::create('status_baia', function (Blueprint $table) {
            $table->id();
            $table->string('descricao')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('status_baia');
    }
}