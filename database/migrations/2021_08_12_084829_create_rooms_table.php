<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->foreignId('user_1_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreignId('user_2_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('turn');
            $table->bigInteger('winner_id')->nullable();

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
        Schema::dropIfExists('rooms');
    }
}
