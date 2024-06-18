<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('name')->nullable();
            $table->string('slug')->nullable();
            $table->enum('type', ['simple', 'plans', 'subscription'])->default('simple')->index();
            $table->text('description')->nullable();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->tinyInteger('status')->default(1)->index();
            $table->boolean('dynamic_pricing')->nullable()->default(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->json('features')->nullable();
            $table->timestamps();
        });

        Schema::create('addons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
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
        Schema::dropIfExists('plans');
        Schema::dropIfExists('addons');
        Schema::dropIfExists('products');
    }
}
