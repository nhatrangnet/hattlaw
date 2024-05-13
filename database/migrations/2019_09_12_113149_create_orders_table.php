<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->default(0);
            $table->integer('admin_id')->unsigned()->default(0);
            $table->string('name');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->text('note')->nullable();
            
            $table->integer('subtotal')->unsigned()->default(0);
            $table->decimal('discount', 10, 0);
            $table->decimal('discount_percent', 8, 0);
            $table->integer('coupon_id')->unsigned()->nullable();
            $table->decimal('shipping_charge', 10, 0);

            $table->integer('total')->unsigned()->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
