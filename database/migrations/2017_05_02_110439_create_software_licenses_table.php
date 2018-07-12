<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoftwareLicensesTable extends Migration
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
		Schema::create('software_licenses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('software_id')->unsigned();
			$table->foreign('software_id')
					->references('id')
					->on('softwares')
                	->onDelete('cascade')
                	->onUpdate('cascade');
			$table->string('key',100);
			$table->integer('usage');
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
		Schema::dropIfExists('software_licenses');
	}

}
