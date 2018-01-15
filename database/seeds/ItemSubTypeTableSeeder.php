<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ItemSubTypeTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		App\ItemSubType::truncate();

		//insert some dummy records
		App\ItemSubType::insert(array(
			[
				'itemtype_id' => 2,
				'name' => 'TV'
			],
			[
				'itemtype_id' => 3,
				'name' => 'Keyboard'
			],
			[
				'itemtype_id' => 3,
				'name' => 'Mouse'
			],

		));
		
	}
}
