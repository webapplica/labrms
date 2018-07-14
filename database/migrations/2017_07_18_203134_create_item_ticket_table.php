<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTicketTable extends Migration
{
    function __construct()
    {
        Schema::disableForeignKeyConstraints(); 
    }

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('item_ticket', function(Blueprint $table)
		{
            $table->integer('item_id')->unsigned();
			$table->foreign('item_id')
					->references('id')
					->on('items')
					->onUpdate('cascade')
					->onDelete('cascade');
            $table->integer('ticket_id')->unsigned();
			$table->foreign('ticket_id')
					->references('id')
					->on('tickets')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->primary([ 'item_id', 'ticket_id' ]);
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
		Schema::dropIfExists('item_ticket');
	}

}
