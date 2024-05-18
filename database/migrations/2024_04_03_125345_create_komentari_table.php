<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomentariTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('komentari', function (Blueprint $table) {
            $table->bigIncrements('komentar_id');
            $table->string('sadržaj',500);
            $table->timestamp('datum_Komentara')->useCurrent();
            $table->unsignedBigInteger('idrecepta');
            $table->unsignedBigInteger('idkorisnika');

            $table->foreign('idrecepta')->references('recept_id')->on('recepti')->onDelete('cascade');
            $table->foreign('idkorisnika')->references('korisnik_id')->on('korisnici')->onDelete('cascade');

            $table->timestamps(); // Ova linija će dodati created_at i updated_at kolone
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('komentari');
    }
}
