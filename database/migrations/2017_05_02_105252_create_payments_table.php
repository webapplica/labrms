<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
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
		Schema::create('payments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('item_id')->unsigned();
			$table->foreign('item_id')
					->references('id')
					->on('items');
			$table->string('receipt_number',100)->nullable();
			$table->string('received_by',100);
			$table->integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')
					->references('id')
					->on('users')
					->onDelete('set null')
					->onUpdate('cascade');
			$table->string('details',100);
			$table->decimal('balance', 8, 2);
			$table->decimal('amount', 8, 2);
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
		Schema::dropIfExists('payments');
	}

}
