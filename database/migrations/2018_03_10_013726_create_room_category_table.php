<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomCategoryTable extends Migration
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
        Schema::create('room_category', function (Blueprint $table) {
            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')
                    ->references('id')
                    ->on('rooms')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
                    ->references('id')
                    ->on('room_categories')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->primary([ 'room_id', 'category_id']);
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
        Schema::dropIfExists('room_category');
    }
}
