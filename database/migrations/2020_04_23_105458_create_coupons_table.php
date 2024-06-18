<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('code');
            $table->tinyInteger('type'); // 1 :  FIXED, 2: PERCENTAGE
            $table->string('amount');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('min_amount')->nullable();
            $table->integer('max_uses')->nullable();
            $table->tinyInteger('use_once')->default(0);
            $table->tinyInteger('on_subtotal')->default(1);
            $table->timestamps();
        });

        Schema::create('coupon_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('coupon_id');
            $table->bigInteger('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_product');
        Schema::dropIfExists('coupons');
    }
}
