<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePcTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pc', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('systemunit_id')->unique();
			$table->foreign('systemunit_id')
					->references('local_id')
					->on('itemprofile')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('monitor_id')->unique()->nullable();
			$table->foreign('monitor_id')
					->references('local_id')
					->on('itemprofile')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('keyboard_id')->unique()->nullable();
			$table->foreign('keyboard_id')
					->references('local_id')
					->on('itemprofile')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('avr_id')->unique()->nullable();
			$table->foreign('avr_id')
					->references('local_id')
					->on('itemprofile')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('oskey',50)->nullable();
			$table->string('mouse_id')->unique()->nullable();
			$table->foreign('mouse_id')
					->references('local_id')
					->on('itemprofile')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('name',50)->nullable();
			$table->string('location')->nullable();
			$table->string('created_by')->nullable();
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
		Schema::drop('pc');
	}

}
