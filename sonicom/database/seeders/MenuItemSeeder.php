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
       ]);

       MenuItem::create([
        'title' => 'Datasets',
        'url' => '/dataset',
       ]);

			 MenuItem::create([
        'title' => 'About SONICOM',
        'url' => 'about',
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
