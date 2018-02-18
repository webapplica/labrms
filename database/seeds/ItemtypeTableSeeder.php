<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ItemTypeTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		App\Itemtype::truncate();

		//insert some dummy records
		App\Itemtype::insert(array(
			[
			   'name' => 'System Unit',
			   'description' => 'Computer set',
			   'category' => 'equipment'
			],
			[
			 'name' => 'Display',
			 'description' => 'Visual aids',
			 'category' => 'equipment'
			],
			[
			 'name' => 'TV',
			 'description' => '',
			 'category' => 'equipment'
			],
			[
			 'name' => 'Keyboard',
			 'description' => '',
			 'category' => 'equipment'
			],
			[
			 'name' => 'Mouse',
			 'description' => '',
			 'category' => 'equipment'
			],
			[
			 'name' => 'AVR',
			 'description' => 'Power Regulator',
			 'category' => 'equipment'
			],
			[
			 'name' => 'Aircon',
			 'description' => 'Cooling appliance',
			 'category' => 'equipment'
			],
			[
			 'name' => 'Projector',
			 'description' => 'Visual aids',
			 'category' => 'equipment'
			],
			[
			 'name' => 'Extension',
			 'description' => 'Extension cord or any other power source',
			 'category' => 'supply'
			],

		));
		
	}
}
