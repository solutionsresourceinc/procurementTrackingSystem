<?php

class DatabaseSeeder extends Seeder {

	public function run()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');

		Eloquent::unguard();

		$this->call('RoleSeeder');
		$this->command->info('The Role table has been seeded!');

		$this->call('UserTableSeeder');
		$this->command->info('The User table has been seeded!');

		$this->call('AssignedRolesSeeder');
		$this->command->info('The Assigned table has been seeded!');

		$this->call('OfficeSeeder');
		$this->command->info('The Office table has been seeded!');

		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
	}

}
