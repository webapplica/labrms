<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\ItemSubType;

class ItemSubTypeTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		ItemSubType::truncate();
		//delete users table records
		DB::table('itemsubtype')->delete();
		//insert some dummy records
		ItemSubType::insert(array(
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
