<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockcards', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');                   
            $table->integer('inventory_id')->unsigned();
            $table->foreign('inventory_id')
                    ->references('id')
                    ->on('inventories')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('reference',100)->nullable();
            $table->string('accountable',100)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('balance')->default(0); 
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
        Schema::dropIfExists('stockcards');
    }
}
