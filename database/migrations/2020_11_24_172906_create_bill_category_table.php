<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            $table->foreign('bill_id')->references('id')->on('bills');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_category');
    }
}
