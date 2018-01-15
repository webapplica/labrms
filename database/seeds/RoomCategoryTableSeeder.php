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
        
        //insert some dummy records
        App\RoomCategory::insert(array(
          ['category' => 'systems development laboratory'], /*, 'description' => ''*/
          ['category' => 'software application laboratory'],/*, 'description' => ''*/
          ['category' => 'programming laboratory'],/*, 'description' => ''*/
          ['category' => 'multimedia laboratory'],/*, 'description' => ''*/
          ['category' => 'computerhardware laboratory']/*, 'description' => ''*/
        ));
   	
    }
}
