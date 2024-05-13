<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_detail', function (Blueprint $table) {
            $table->integer('tag_id')->unsigned();;
            $table->integer('blog_news_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->tinyInteger('blog_news_status')->default(1);
            $table->tinyInteger('product_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_detail');
    }
}
