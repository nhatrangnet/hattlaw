<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_news', function (Blueprint $table) {
            $table->increments('id');
            // $table->string('category_id',50)->nullable();
            // $table->string('tag_id',50)->nullable();
            $table->integer('admin_id')->unsigned()->nullable();
            $table->string('title');
            $table->string('slug');
            $table->text('summary')->nullable();
            $table->text('content')->nullable();
            $table->text('image')->nullable();
            $table->text('image_slide')->nullable();
            $table->string('tags')->nullable();
            $table->string('metakey')->nullable();
            $table->text('metades')->nullable();
            $table->string('metarobot')->nullable();
            $table->integer('hit')->default(1)->unsigned();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('blog_news');
    }
}
