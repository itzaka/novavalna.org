<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotAlbumPostTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('album_post', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('album_id')->unsigned()->index();
			$table->integer('post_id')->unsigned()->index();
			$table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('album_post');
	}

}
