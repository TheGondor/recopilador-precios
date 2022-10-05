<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('search_dates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('search_id');
            $table->date('date');
            $table->unsignedBigInteger('quantity');
            $table->foreign('search_id')->references('id')->on('searchs');
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
        Schema::dropIfExists('search_dates');
    }
};
