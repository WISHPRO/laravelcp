<?php

use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function($table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('content')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 255)->nullable();
            $table->string('meta_keywords', 255)->nullable();
            $table->string('banner', 255)->nullable();
            $table->integer('display_author')->unsigned()->nullable();
            $table->integer('allow_comments')->unsigned()->nullable();
            $table->string('template', 255)->nullable();
            $table->integer('parent')->unsigned()->nullable();
            $table->integer('display_navigation')->unsigned()->nullable();
            $table->timestamps();
 			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }

}