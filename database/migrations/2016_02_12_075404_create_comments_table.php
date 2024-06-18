<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('content')->nullable();
            $table->string('type', 20)->default('comment');
            $table->bigInteger('parent')->nullable();
            $table->tinyInteger('rating')->nullable();
            $table->bigInteger('object_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('to_id')->nullable()->index();
            $table->dateTime('seen_at')->nullable();
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
        Schema::drop('comments');
    }
}
