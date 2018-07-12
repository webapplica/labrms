<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoftwareTypesTable extends Migration
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
        Schema::create('software_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type',100)->unique();
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
        Schema::dropIfExists('software_types');
    }
}
