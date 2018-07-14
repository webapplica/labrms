<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTicketTable extends Migration
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
		Schema::create('user_ticket', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('user_id')->unsigned();
			$table->foreign('user_id')
					->references('id')
					->on('users')
					->onUpdate('cascade')
					->onDelete('cascade');
            $table->integer('ticket_id')->unsigned();
			$table->foreign('ticket_id')
					->references('id')
					->on('tickets')
					->onUpdate('cascade')
					->onDelete('cascade');
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
		Schema::dropIfExists('user_ticket');
	}

}
