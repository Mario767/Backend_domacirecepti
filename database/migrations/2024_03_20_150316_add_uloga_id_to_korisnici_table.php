<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUlogaIdToKorisniciTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('korisnici', function (Blueprint $table) {
            $table->unsignedInteger('uloga_id')->nullable();


            $table->foreign('uloga_id')
                ->references('id')
                ->on('uloge')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('korisnici', function (Blueprint $table) {
            $table->dropForeign(['uloga_id']);
            $table->dropColumn('uloga_id');
        });
    }
};
