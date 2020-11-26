<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillPlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_place', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('place_id');
            $table->timestamps();

            $table->foreign('bill_id')->references('id')->on('bills');
            $table->foreign('place_id')->references('id')->on('places');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_place');
    }
}
