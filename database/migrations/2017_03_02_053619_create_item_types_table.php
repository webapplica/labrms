<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTypesTable extends Migration
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
		Schema::create('item_types', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name',50)->unique();
			$table->string('description',450)->nullable();
			$table->string('category',450)->nullable();
			$table->integer('parent_id')->unsigned()->nullable();
			$table->foreign('parent_id')
					->references('id')
					->on('item_types')
					->onUpdate('cascade')
					->onDelete('set null');
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
		Schema::dropIfExists('item_types');
	}

}
