<?php

use App\Models\Room\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoomCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();
        
        Category::insert(array(
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
