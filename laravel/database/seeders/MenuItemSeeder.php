<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
		{
			 /*
       MenuItem::create([
        'title' => 'Home',
        'url' => '/',
			 ]);
			 */

       MenuItem::create([
        'id' => '1',
        'title' => 'RADAR',
        'url' => '/radar',
        'authenticated' => 0,
       ]);

       MenuItem::create([
        'id' => '2',
        'title' => 'Databases',
        'url' => '/databases',
        'authenticated' => 0,
       ]);

       /*
       MenuItem::create([
        'id' => '3',
        'title' => 'Create Database',
        'url' => '/databases/create',
        'authenticated' => 1,
       ]);
        */

       MenuItem::create([
        'id' => '6',
        'title' => 'Datasets',
        'url' => '/datasets',
        'authenticated' => 0,
       ]);

        MenuItem::create([
        'id' => '4',
        'title' => 'About SONICOM',
        'route' => 'about',
        'authenticated' => 0,
       ]);

       MenuItem::create([
        'id' => '5',
        'title' => 'Admin',
        'route' => 'filament.admin.pages.dashboard',
        'authenticated' => 2,
			 ]);
			/*
      
       MenuItem::create([
        'id' => '6',
        'title' => 'Databases',
        'route' => 'databases.index',
        'parent_id' => '5',
        'authenticated' => 2,
       ]);
				*/


/*
       $services = MenuItem::create([
        'title' => 'Services',
        'url' => '/services',
       ]);

       MenuItem::create([
        'title' => 'Web Development',
        'url' => '/services/web-development',
        'parent_id' => $services->id,
       ]);
        MenuItem::create([
            'title' => 'Mobile Development',
            'url' => '/services/mobile-development',
            'parent_id' => $services->id,
        ]);
 */
			  /*
        MenuItem::create([
            'title' => 'Contact Us',
            'url' => '/contact-us',
				]);
			  */
		}
}
