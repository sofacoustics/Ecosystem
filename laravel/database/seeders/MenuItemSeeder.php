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
        'title' => 'Data',
        'url' => '/data',
        'authenticated' => false,
       ]);

       MenuItem::create([
        'title' => 'Databases',
        'url' => '/databases',
        'authenticated' => false,
       ]);

       MenuItem::create([
        'title' => 'Create Database',
        'url' => '/databases/create',
       ]);

			 MenuItem::create([
        'title' => 'About SONICOM',
        'url' => '/about',
        'authenticated' => false,
       ]);
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