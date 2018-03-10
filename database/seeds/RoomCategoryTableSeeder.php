<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\RoomCategory;
class RoomCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\RoomCategory::truncate();
        
        App\RoomCategory::insert(array(
          [ 'name' => 'Systems Development'], 
          [ 'name' => 'Software Application'],
          [ 'name' => 'Programming'],
          [ 'name' => 'Multimedia'],
          [ 'name' => 'Computer Hardware'],
          [ 'name' => 'Web Development'],
          [ 'name' => 'Networking' ],
          [ 'name' => 'Consultation'],
          [ 'name' => 'Database Management'],
        ));
   	
    }
}
