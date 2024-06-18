<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('taxonomy', 20)->default('category');
            $table->bigInteger('parent')->default(0);
            $table->text('description')->nullable();
            $table->string('route')->nullable();
            $table->integer('product_count')->default(0)->nullable();
        });

        Schema::create('term_relationships', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('term_id')->index();
            $table->primary(['product_id', 'term_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('terms');
        Schema::drop('term_relationships');
    }
}
