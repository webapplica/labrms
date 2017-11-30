<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultyView extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			CREATE VIEW faculty_v AS
                SELECT id,CONCAT( lastname,', ', firstname , middlename ) AS fullname, lastname,firstname,middlename, accesslevel,username, contactnumber,email,type,status,created_at,updated_at
                FROM user
                WHERE type IN ('faculty','FACULTY','Faculty');
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("DROP VIEW faculty_v");
	}

}
