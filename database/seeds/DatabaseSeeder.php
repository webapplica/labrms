<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		//disable foreign key check for this connection before running seeders
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		// DB::table('itemprofile')->truncate();
		// DB::table('item_ticket')->truncate();
		// DB::table('room_ticket')->truncate();
		// DB::table('pc_ticket')->truncate();
		// DB::table('user_ticket')->truncate();
		// DB::table('ticket')->truncate();
		// DB::table('roominventory')->truncate();
		// DB::table('inventory')->truncate();
		// DB::table('software')->truncate();
		// DB::table('softwarelicense')->truncate();
		// DB::table('semester')->truncate();
		// DB::table('academic_years')->truncate();
		// DB::table('reservation')->truncate();
		// DB::table('roomreservation')->truncate();
		// DB::table('receipt')->truncate();
		// // DB::table('unit')->truncate();
		// DB::table('ticket_attachment')->truncate();
		// DB::table('supply')->truncate();
		// DB::table('supplyhistory')->truncate();
		// DB::table('supplylendlog')->truncate();
		// DB::table('reservation')->truncate();
		// DB::table('item_reservation')->truncate();
		// DB::table('inventory')->truncate();

		$this->call(UserTableSeeder::class);
		$this->call(RoomTableSeeder::class);
		$this->call(ItemTypeTableSeeder::class);
		$this->call(ItemSubTypeTableSeeder::class);
		$this->call(TicketTypeTableSeeder::class);
		$this->call(PurposeTableSeeder::class);
		$this->call(SoftwareTypeTableSeeder::class);
		$this->call(RoomCategoryTableSeeder::class);
		$this->call(LanguageTableSeeder::class);
		$this->call(SettingsTableSeeder::class);
		$this->call(UnitTableSeeder::class);

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

	}

}
