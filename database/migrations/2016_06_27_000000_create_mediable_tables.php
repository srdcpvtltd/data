<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('disk', 32);
            $table->string('directory');
            $table->string('filename');
            $table->string('extension', 32);
            $table->string('mime_type', 128);
            $table->string('aggregate_type', 32);
            $table->integer('size')->unsigned();
            $table->timestamps();

            $table->index(['disk', 'directory']);
            $table->unique(['disk', 'directory', 'filename', 'extension']);
            $table->index('aggregate_type');
        });

        Schema::create('mediables', function (Blueprint $table) {
            $table->bigInteger('media_id')->unsigned();
            $table->string('mediable_type');
            $table->bigInteger('mediable_id')->unsigned();
            $table->string('tag');
            $table->bigInteger('order')->unsigned();

            $table->primary(['media_id', 'mediable_type', 'mediable_id', 'tag']);
            $table->index(['mediable_id', 'mediable_type']);
            $table->index('tag');
            $table->index('order');
            $table->foreign('media_id')
                ->references('id')->on('media')
                ->onDelete('cascade');
        });

        Schema::create('media_meta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('media_id');
            $table->string('size_name');
            $table->string('path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media_meta');
        Schema::drop('mediables');
        Schema::drop('media');
    }
};
