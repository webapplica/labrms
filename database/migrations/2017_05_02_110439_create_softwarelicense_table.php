<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoftwarelicenseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('softwarelicense', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('software_id')->unsigned();
			$table->foreign('software_id')
					->references('id')
					->on('software')
                	->onDelete('cascade')
                	->onUpdate('cascade');
			$table->string('key',100);
			$table->integer('multipleuse');
			$table->integer('inuse');
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
		Schema::drop('softwarelicense');
	}

}
