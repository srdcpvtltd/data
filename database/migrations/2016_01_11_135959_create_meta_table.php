<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_meta', function (Blueprint $table) {

            $table->bigIncrements('product_meta_id');
            $table->string('key');
            $table->longText('value')->nullable();
            $table->bigInteger('product_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_meta');
    }
}
