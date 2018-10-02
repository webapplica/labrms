<?php

use App\Models\Ticket\Type;
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
        Type::truncate();

	    Type::insert(array(
	    	[ 'name'=>'Complaint'],
			[ 'name'=>'Action'],
			[ 'name'=>'Transfer'],
			[ 'name'=>'Maintenance'],
			[ 'name'=>'Lent'],
			[ 'name'=>'Incident']
		));
	}

}
