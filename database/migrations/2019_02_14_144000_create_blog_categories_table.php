<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->nullable();
            $table->integer('admin_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('cover')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('hit')->default(1)->unsigned();
            $table->string('metakey')->nullable();
            $table->string('metarobot')->nullable();
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
        Schema::dropIfExists('blog_categories');
    }
}
