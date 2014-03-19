<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePollsAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('polls_answers', function(Blueprint $table) {
			$table->increments('id');
			$table->string('answer');
			$table->integer('poll_id')->unsigned()->index();
			$table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
			$table->integer('votes');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('polls_answers');
	}

}
