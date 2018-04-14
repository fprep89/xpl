<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLquestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lquestions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('reference');
            $table->string('type');
            $table->longText('question');
            $table->longText('a')->nullable();
            $table->longText('b')->nullable();
            $table->longText('c')->nullable();
            $table->longText('d')->nullable();
            $table->longText('e')->nullable();
            $table->longText('answer')->nullable();
            $table->longText('explanation')->nullable();
            $table->longText('dynamic')->nullable();
            $table->integer('repository_id')->unsigned();
            $table->integer('passage_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('stage')->default(1);
            $table->integer('status')->default(0);
            $table->foreign('repository_id')->references('id')->on('repositories')->onDelete('cascade');
            $table->foreign('passage_id')->references('id')->on('lpassages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('lquestions');
    }
}