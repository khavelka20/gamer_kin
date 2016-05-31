<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamerAffinitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gamer_affinities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gamer_id');
            $table->string('genre_id');
            $table->boolean('affinity');
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
        Schema::drop('gamer_affinities');
    }
}
