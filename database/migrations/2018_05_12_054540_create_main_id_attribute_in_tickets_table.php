<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainIdAttributeInTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // public function up()
    // {
    //     Schema::table('tickets', function (Blueprint $table) {

    //         if(! Schema::hasColumn('tickets', 'main_id'))
    //             $table->integer('main_id')->unsigned();

    //         $table->foreign('main_id')
    //                 ->references('id')
    //                 ->on('tickets')
    //                 ->onDelete('cascade')
    //                 ->onUpdate('cascade');
    //     });
    // }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {

            if(Schema::hasColumn('main_id')) {       
                Schema::disableForeignKeyConstraints(); 
                $table->drop('main_id');        
                Schema::enableForeignKeyConstraints();
            }
        });
    }
}
