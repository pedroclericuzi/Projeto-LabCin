<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaiaTable extends Migration
{
    public function up()
    {
        Schema::create('baia', function (Blueprint $table) {
            $table->id();
            $table->integer('num_baia');
            $table->string('user_usando',255)->nullable();
            $table->foreignId('status_baia_id')->constrained('status_baia');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('baia');
    }
}