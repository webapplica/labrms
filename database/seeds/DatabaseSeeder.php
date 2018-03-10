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

		DB::table('items')->truncate();
		DB::table('item_ticket')->truncate();
		DB::table('room_ticket')->truncate();
		DB::table('workstation_ticket')->truncate();
		DB::table('user_ticket')->truncate();
		DB::table('tickets')->truncate();
		DB::table('inventories')->truncate();
		DB::table('softwares')->truncate();
		DB::table('software_licenses')->truncate();
		DB::table('semesters')->truncate();
		DB::table('academic_years')->truncate();
		DB::table('reservations')->truncate();
		DB::table('room_reservation')->truncate();
		DB::table('receipts')->truncate();
		DB::table('units')->truncate();
		DB::table('ticket_attachments')->truncate();
		DB::table('reservations')->truncate();
		DB::table('item_reservation')->truncate();

		$this->call(UserTableSeeder::class);
		$this->call(RoomCategoryTableSeeder::class);
		$this->call(RoomTableSeeder::class);
		$this->call(ItemTypeTableSeeder::class);
		$this->call(TicketTypeTableSeeder::class);
		$this->call(PurposeTableSeeder::class);
		$this->call(SoftwareTypeTableSeeder::class);
		$this->call(LanguageTableSeeder::class);
		$this->call(SettingsTableSeeder::class);
		$this->call(UnitTableSeeder::class);

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');

	}

}
