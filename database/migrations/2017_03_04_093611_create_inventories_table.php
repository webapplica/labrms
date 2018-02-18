<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('inventories', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('code')->unique();
			$table->string('brand',100)->nullable();
			$table->string('model',100)->nullable();
			$table->string('details',1000)->nullable();

            $table->string('unit_name')->nullable();
            $table->foreign('unit_name')
                    ->references('name')
                    ->on('units') 
                    ->onUpdate('cascade')
                    ->onDelete('set null');

            $table->integer('itemtype_id')->unsigned();
            $table->foreign('itemtype_id')
                    ->references('id')
                    ->on('item_types')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')
					->references('id')
					->on('users')
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
		Schema::drop('inventories');
	}

}
