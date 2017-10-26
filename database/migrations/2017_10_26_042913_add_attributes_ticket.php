<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributesTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('ticket', function(Blueprint $table)
      {
        $table->string('closed_by',254)->nullable();
        $table->string('validated_by',254)->nullable();
        $table->datetime('deadline')->nullable();
        $table->boolean('trashable')->nullable();
        $table->string('severity')->nullable();
        $table->string('nature')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
