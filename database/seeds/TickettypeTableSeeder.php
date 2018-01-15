<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TicketTypeTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        App\TicketType::truncate();

	    App\TicketType::insert(array(
	    	[ 'name'=>'Complaint'],
			[ 'name'=>'Action Taken'],
			[ 'name'=>'Transfer'],
			[ 'name'=>'Maintenance'],
			[ 'name'=>'Lent'],
			[ 'name'=>'Incident']
		));
	}

}
