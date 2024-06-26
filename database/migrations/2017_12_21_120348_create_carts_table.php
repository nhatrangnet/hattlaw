<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cookie');
            $table->integer('auth_user')->unsigned()->nullable();
            $table->decimal('subtotal', 10, 0);
            $table->decimal('discount', 10, 0);
            $table->decimal('discount_percentage', 8, 0);
            $table->integer('coupon_id')->unsigned()->nullable();
            $table->decimal('shipping_charges', 10, 0);
            $table->decimal('net_total', 10, 0);
            $table->decimal('tax', 10, 0);
            $table->decimal('total', 10, 0);
            $table->decimal('round_off', 10, 0);
            $table->decimal('payable', 10, 0);
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
        Schema::dropIfExists('carts');
    }
}
