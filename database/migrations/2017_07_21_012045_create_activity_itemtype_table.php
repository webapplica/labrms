<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityItemtypeTable extends Migration
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
		Schema::create('activity_itemtype', function(Blueprint $table)
		{
			$table->string('activity_id')
					->references('id')
					->on('activities')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->string('itemtype_id')
					->references('id')
					->on('item_types')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->primary(['activity_id', 'itemtype_id']);
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
		Schema::dropIfExists('activity_itemtype');
	}

}
