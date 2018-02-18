<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->string('title',100);
			$table->longtext('details');
			$table->string('author');

			/**
			 * ticket types consists of 
			 * lent, received, complaint, maintenance, 
			 * item transfer, ticket transfer, condemn
			 */
			$table->integer('type_id')->unsigned();
			$table->foreign('type_id')
					->references('id')
					->on('ticket_types')
					->onUpdate('cascade')
					->onDelete('cascade');

			/**
			 * persons accountable for the tickets
			 */
			$table->integer('staff_id')->unsigned()->nullable();
			$table->foreign('staff_id')
					->references('id')
					->on('users')
					->onDelete('cascade')
					->onUpdate('cascade');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
					->references('id')
					->on('users')
					->onDelete('cascade')
					->onUpdate('cascade');

			/**
			 * ticket history
			 */
			$table->integer('predecessor_id')->unsigned()->nullable();
			$table->foreign('predecessor_id')
					->references('id')
					->on('tickets')
					->onUpdate('cascade')
					->onDelete('cascade');

			/**
			 * additional informations
			 */
			$table->string('comments')->nullable();
	        $table->string('closed_by',254)->nullable();
	        $table->string('validated_by',254)->nullable();
	        $table->datetime('deadline')->nullable();
	        $table->boolean('trashable')->nullable();

	        /**
	         * severity of ticket from 1 - 100
	         * the lesser the number, the lesser the severity
	         * of ticket
	         */
	        $table->integer('severity')->default(1);
	        $table->string('nature')->nullable();
			$table->string('status');
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
		Schema::drop('tickets');
	}

}
