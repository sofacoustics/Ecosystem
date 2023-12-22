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
        'authenticated' => 0,
       ]);

       MenuItem::create([
        'title' => 'Databases',
        'url' => '/databases',
        'authenticated' => 0,
       ]);

       MenuItem::create([
        'title' => 'Create Database',
        'url' => '/databases/create',
        'authenticated' => 1,
       ]);

			 MenuItem::create([
        'title' => 'About SONICOM',
        'url' => '/about',
        'authenticated' => 0,
       ]);

       MenuItem::create([
        'title' => 'Admin',
        'url' => '/admin',
        'route' => 'admin.index',
        'authenticated' => 2,
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