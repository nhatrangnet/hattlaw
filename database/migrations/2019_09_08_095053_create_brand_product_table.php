<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('brand_id')->unsigned()->index();
            // $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('product_id')->unsigned()->index();
            // $table->foreign('product_id')->references('id')->on('products');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brand_product');
    }
}
