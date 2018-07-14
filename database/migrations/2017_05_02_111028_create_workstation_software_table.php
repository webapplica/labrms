<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkstationSoftwareTable extends Migration
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
		Schema::create('workstation_software', function(Blueprint $table)
		{
			$table->integer('workstation_id')->unsigned();
			$table->foreign('workstation_id')
					->references('id')
					->on('workstations')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->integer('software_id')->unsigned();
			$table->foreign('software_id')
					->references('id')
					->on('softwares')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->integer('license_id')->unsigned()->nullable();
			$table->foreign('license_id')
					->references('id')
					->on('software_licenses')
					->onUpdate('cascade')
					->onDelete('cascade');
			$table->primary(['workstation_id', 'software_id']);
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
		Schema::dropIfExists('workstation_software');
	}

}
