<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUlogeTable extends Migration
{
    public function up()
    {
        Schema::create('uloge', function (Blueprint $table) {
            $table->increments('id');
            $table->string('naziv_uloge');

        });
    }

    public function down()
    {
        Schema::dropIfExists('uloge');
    }
}
