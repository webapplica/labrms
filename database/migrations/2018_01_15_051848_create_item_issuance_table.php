<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemIssuanceTable extends Migration
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
        Schema::create('item_issuance', function (Blueprint $table) {
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')
                    ->references('id')
                    ->on('items')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->integer('issuance_id')->unsigned();
            $table->foreign('issuance_id')
                    ->references('id')
                    ->on('issuances')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->primary([ 'item_id', 'issuance_id' ]);
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
        Schema::disableForeignKeyConstraints(); 
        Schema::dropIfExists('item_issuance');
    }
}
