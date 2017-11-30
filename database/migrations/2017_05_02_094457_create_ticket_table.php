<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('tickettype',100);
			$table->string('ticketname',100);
			$table->string('details',500);
			$table->string('author',100);
			$table->string('staffassigned',100)->nullable();
			$table->integer('ticket_id')->unsigned()->nullable();
			$table->foreign('ticket_id')
					->references('id')
					->on('ticket')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('status');
			$table->string('comments')->nullable();
	        $table->string('closed_by',254)->nullable();
	        $table->string('validated_by',254)->nullable();
	        $table->datetime('deadline')->nullable();
	        $table->boolean('trashable')->nullable();
	        $table->string('severity')->nullable();
	        $table->string('nature')->nullable();
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
		Schema::drop('ticket');
	}

}
