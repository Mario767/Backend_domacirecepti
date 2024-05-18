<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recepti', function (Blueprint $table) {
            $table->id('recept_id');
            $table->string('naziv_recepta', 60);
            $table->text('opis')->limit(1000); // Ograničava duljinu opisa na 1000 znakova
            $table->string('slika')->nullable(); // Dodaje atribut za sliku
            $table->enum('vrstajela', ['Glavno jelo', 'Predjelo', 'Desert']);
            $table->foreignId('korisnik_id')->constrained('korisnici', 'korisnik_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recepti');
    }
};
