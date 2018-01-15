<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkstationTicketTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('workstation_ticket', function(Blueprint $table)
		{
            $table->integer('workstation_id')->unsigned();
			$table->foreign('workstation_id')
					->references('id')
					->on('workstations')
					->onUpdate('cascade')
					->onDelete('cascade');
            $table->integer('ticket_id')->unsigned();
			$table->foreign('ticket_id')
					->references('id')
					->on('tickets')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->primary([ 'workstation_id', 'ticket_id' ]);
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
		Schema::drop('workstation_ticket');
	}

}
