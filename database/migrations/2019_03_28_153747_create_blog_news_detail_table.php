<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogNewsDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_news_detail', function (Blueprint $table) {
            $table->integer('blog_news_id')->unsigned();;
            $table->integer('blog_category_id')->unsigned();
            $table->tinyInteger('blog_news_status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_news_detail');
    }
}
