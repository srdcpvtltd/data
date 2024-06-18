<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
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
            $table->bigInteger('user_id')->unsigned()->index();
            $table->string('status', 32)->default('pending');
            $table->timestamps();
        });

        Schema::create('order_meta', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned()->index();
            $table->string('key', 255);
            $table->text('value');
        });

        Schema::create('order_items', function ( Blueprint $table ) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned()->index();
            $table->bigInteger('item_id')->unsigned()->index();
            $table->string('item_type', 45);
            $table->decimal('price', 10, 2);
        });

        Schema::create('order_itemmeta', function( Blueprint $table ){
            $table->bigIncrements('id');
            $table->bigInteger('order_item_id')->unsigned()->index();
            $table->string('key', 255);
            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('orders');
        Schema::drop('order_meta');
        Schema::drop('order_items');
        Schema::drop('order_itemmeta');
    }
}
