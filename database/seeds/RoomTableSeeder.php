<?php

use App\Models\Room\Room;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoomTableSeeder extends Seeder {

  /**
   * Run the database seeds.
   *
   * @return void
   */
	 
	public function run()
	{
    Room::truncate();
   	Room::insert(array(
       [
        'name' => 'S501',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ],
       [
        'name' => 'S502',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ],
       [
        'name' => 'S503',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ],
       [
        'name' => 'S504',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ],
       [
        'name' => 'Consultation Room',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ],
       [
        'name' => 'Faculty Room',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ],
       [
        'name' => 'Server',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ],
       [
        'name' => 'S508',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ],
       [
        'name' => 'S510',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ],
       [
        'name' => 'S511',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
       ]
    ));
	}


}
