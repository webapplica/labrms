<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancelledAttributeInReservation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if(! Schema::hasColumn('reservations', 'is_cancelled')) {
                $table->datetime('is_cancelled')->nullable();
                $table->datetime('is_approved')->nullable();
                $table->datetime('is_claimed')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            if(Schema::hasColumn('reservations', 'is_cancelled')) { 
                $table->drop('is_cancelled');        
            }
        });
    }
}
