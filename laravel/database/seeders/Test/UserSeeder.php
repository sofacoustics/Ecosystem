<?php

namespace Database\Seeders\Test;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		// Possibly create a hashed password using php artisan tinker
		// Then write echo Hash::make('somestring');
		$user = User::updateOrCreate([ 'email' => 'jonnie@floorspot.org' ],
			[
				'name' => 'Jonnie Admin',
				'password' => '$2y$10$eljLQEcv5TU/7sgcBQZCMupOVkHPKIrPPXOgKmSs/MKlI9i.Mnp66',
				'orcid' => '0000-0000-0000-0000',
				'orcid_verified_at' => '2025-06-05 09:42:20'
			]
		);
		$user->assignRole('admin');

		$user = User::updateOrCreate([ 'email' => 'piotr.majdak@oeaw.ac.at' ],
			[
				'name' => 'Piotr Majdak',
				'password' => '$2y$10$oO3j2Rv8E5qlsFlEWZMbk..qpiEcABAQkFATlXeHI.ZN/xI2LaDL.',
				'orcid' => '0000-0003-1511-6164'
			]
		);
		$user->assignRole('admin');

		$user = User::updateOrCreate([ 'email' => 'jonathan.stuefer@oeaw.ac.at' ],
			[
				'name' => 'Jonnie User',
				'password' => '$2y$10$eljLQEcv5TU/7sgcBQZCMupOVkHPKIrPPXOgKmSs/MKlI9i.Mnp66',
				'orcid' => '0000-0000-0000-0001'
			]
		);
				
		$user = User::updateOrCreate([ 'email' => 'michael.mihocic@oeaw.ac.at' ],
			[
				'name' => 'Michael Mihocic',
				'password' => '$2y$10$en9XzixWc7wEn25LoZOn8.M7DYodeQa7T2najcOp1h5B/OCMiPKS6',
				'orcid' => '0000-0002-0800-6293'
			]
		);
		$user->assignRole('admin');
	}
}
