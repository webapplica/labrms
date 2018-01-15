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
			$table->string('systemunit_id')->unique();
			$table->foreign('systemunit_id')
					->references('local_id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('monitor_id')->unique()->nullable();
			$table->foreign('monitor_id')
					->references('local_id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('keyboard_id')->unique()->nullable();
			$table->foreign('keyboard_id')
					->references('local_id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('avr_id')->unique()->nullable();
			$table->foreign('avr_id')
					->references('local_id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('oskey',50)->nullable();
			$table->string('mouse_id')->unique()->nullable();
			$table->foreign('mouse_id')
					->references('local_id')
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
