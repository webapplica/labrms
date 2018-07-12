<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemReservationTable extends Migration
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
		Schema::create('item_reservation', function(Blueprint $table)
		{
			$table->integer('reservation_id')->unsigned();
			$table->foreign('reservation_id')
					->references('id')
					->on('reservations')
					->onupdate('cascade')
					->onDelete('cascade');
			$table->integer('item_id')->unsigned();
			$table->foreign('item_id')
					->references('id')
					->on('items')
					->onupdate('cascade')
					->onDelete('cascade');
			$table->boolean('is_claimed')->default(0);
			$table->boolean('is_returned')->default(0);
			$table->primary([ 'item_id', 'reservation_id' ]);
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('item_reservation');
	}

}
