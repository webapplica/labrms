<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkstationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workstations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('systemunit_id')->unique()->unsigned();
			$table->foreign('systemunit_id')
					->references('id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->integer('monitor_id')->unsigned()->unique()->nullable();
			$table->foreign('monitor_id')
					->references('id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->integer('keyboard_id')->unsigned()->unique()->unsigned()->nullable();
			$table->foreign('keyboard_id')
					->references('id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->integer('avr_id')->unsigned()->unique()->unsigned()->nullable();
			$table->foreign('avr_id')
					->references('id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('oskey',50)->nullable();
			$table->integer('mouse_id')->unsigned()->unique()->unsigned()->nullable();
			$table->foreign('mouse_id')
					->references('id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('name',50)->nullable();
			$table->integer('room_id')->unsigned()->nullable();
			$table->foreign('room_id')
					->references('id')
					->on('rooms')
					->onUpdate('cascade')
					->onDelete('cascade');
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
		Schema::drop('workstations');
	}

}
