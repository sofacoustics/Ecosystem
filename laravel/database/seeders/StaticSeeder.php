<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/*
 * The 'Static' seeder should seed all data which the sonicom developers
 * have generated. E.g. Widgets,
 *
 * This should be indempotent, meaning that you can run it multiple times and
 * it will always achieve the same state. To do this - we use updateOrCreate 
 * with a unique 'key' string rather than using a primary key.
 *
 */
class StaticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
	{
		//
		// These seeders are for data which the user does *not* change.
		//
		$this->call(
			[
				RolesAndPermissionsSeeder::class,
				MenuItemSeeder::class,
				MetadataschemaSeeder::class,
				ServiceSeeder::class,
				WidgetSeeder::class,
				DatafiletypeSeeder::class,
				DatafiletypeWidgetSeeder::class,
			]
		);
    }
}
