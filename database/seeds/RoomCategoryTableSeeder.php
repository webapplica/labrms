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
        
        DB::table('room_category')->insert([
          [ 'room_id' => 1, 'category_id' => 6],
          [ 'room_id' => 2, 'category_id' => 7],
          [ 'room_id' => 3, 'category_id' => 7],
          [ 'room_id' => 4, 'category_id' => 5],
          [ 'room_id' => 4, 'category_id' => 7],
          [ 'room_id' => 5, 'category_id' => 8],
          [ 'room_id' => 8, 'category_id' => 1],
          [ 'room_id' => 8, 'category_id' => 4],
          [ 'room_id' => 10, 'category_id' => 4],
          [ 'room_id' => 10, 'category_id' => 6],
          [ 'room_id' => 10, 'category_id' => 9],
          [ 'room_id' => 11, 'category_id' => 10],
        ]);
   	
    }
}
