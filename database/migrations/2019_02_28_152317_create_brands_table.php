<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('cover')->nullable();
            $table->string('metakey')->nullable();
            $table->text('metades')->nullable();
            $table->string('metarobot')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('brands');
    }
}
