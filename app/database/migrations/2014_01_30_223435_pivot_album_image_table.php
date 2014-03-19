<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PivotAlbumImageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('album_image', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('album_id')->unsigned()->index();
			$table->integer('image_id')->unsigned()->index();
			$table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
			$table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
		});
	}



	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('album_image');
	}

}
