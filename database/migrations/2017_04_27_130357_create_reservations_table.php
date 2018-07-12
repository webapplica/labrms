<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
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
		Schema::create('reservations', function(Blueprint $table)
		{	
			$table->increments('id');
			$table->string('accountable')->nullable();
			$table->string('reservee')->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')
					->references('id')
					->on('users')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->datetime('start');
			$table->datetime('end');
			$table->string('purpose',100);
			$table->string('location',100);
			$table->integer('faculty_id')->unsigned()->nullable();
			$table->foreign('faculty_id')
					->references('id')
					->on('users')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('remarks',100)->nullable();
			$table->boolean('is_approved')->nullable();
			$table->boolean('is_claimed')->default(0);
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
		Schema::dropIfExists('reservations');
	}

}
