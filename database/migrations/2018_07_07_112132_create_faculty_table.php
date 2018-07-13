<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacultyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
 			$table->increments('id');
			$table->string('title',100);
			$table->string('firstname',100);
			$table->string('middlename',50)->nullable();
			$table->string('lastname',50);
			$table->string('contactnumber',50);
			$table->string('suffix', 5);
			$table->string('email',100);
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
        Schema::dropIfExists('faculties');
    }
}
