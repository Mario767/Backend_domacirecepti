<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOcjeneTable extends Migration
{
    public function up()
    {
        Schema::create('ocjene', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('korisnik_id');
            $table->unsignedBigInteger('recept_id');
            $table->integer('ocjena')->unsigned()->min(1)->max(5);
            $table->timestamps();

            $table->foreign('korisnik_id')->references('korisnik_id')->on('korisnici')->onDelete('cascade');
            $table->foreign('recept_id')->references('recept_id')->on('recepti')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ocjene');
    }
}
