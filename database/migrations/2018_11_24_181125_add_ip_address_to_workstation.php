<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIpAddressToWorkstation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workstations', function (Blueprint $table) {
            $table->string('ip_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workstations', function (Blueprint $table) {
            if(Schema::hasColumn('workstations', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
        });
    }
}
