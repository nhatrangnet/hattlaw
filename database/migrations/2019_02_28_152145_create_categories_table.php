<?php
// use Kalnoy\Nestedset\NestedSet;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admin_id')->unsigned()->nullable();
            $table->string('name')->unique();
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('hit')->default(1)->unsigned();
            $table->string('metakey')->nullable();
            $table->text('metades')->nullable();
            $table->string('metarobot')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // NestedSet::columns($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
