<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLostItemsTable extends Migration
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
        Schema::create('lost_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identifier')->unique();
            $table->string('description')->nullable();
            $table->string('imagepath')->nullable();
            $table->datetime('date_found');
            $table->string('claimant')->nullable();
            $table->string('claimant_desc')->nullable();
            $table->datetime('date_claimed')->nullable();
            $table->string('status')->nullable()->default('unclaimed');
            $table->string('added_by')->nullable();
            $table->string('processed_by')->nullable();
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
        Schema::dropIfExists('lost_items');
    }
}
