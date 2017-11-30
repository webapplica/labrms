<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemprofileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('itemprofile', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('local_id')->unique();
			$table->integer('inventory_id')->unsigned();
			$table->foreign('inventory_id')
					->references('id')
					->on('inventory')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->integer('receipt_id')->unsigned();
			$table->foreign('receipt_id')
					->references('id')
					->on('receipt')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('propertynumber',100)->unique()->nullable();
			$table->string('serialnumber',100)->unique()->nullable();
			$table->string('location',100)->nullable();
			$table->date('datereceived')->nullable();
            $table->string('profiled_by')->nullable();
			$table->string('warranty',100)->nullable();
            $table->dateTime('lent_at')->nullable();
            $table->string('lent_by')->nullable();
            $table->dateTime('deployed_at')->nullable();
            $table->string('deployed_by')->nullable();
			$table->string('status')->nullable();
			$table->timestamps();
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
		Schema::drop('itemprofile');
	}

}
