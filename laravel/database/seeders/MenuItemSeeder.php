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

		// the 'updateOrCreate' function looks for a record matching the first parameter's pairs ('title' here)
		// and updates it, if it exists, or creates it otherwise.
		MenuItem::updateOrCreate([ 'key' => 'databases' ], [ 'title' => 'Databases', 'url' => '/databases', 'authenticated' => 0, ]);
		MenuItem::updateOrCreate([ 'key' => 'tools' ], [ 'title' => 'Tools', 'url' => '/tools', 'authenticated' => 0, ]);
		MenuItem::updateOrCreate([ 'key' => 'scenarios' ], [ 'title' => 'Scenarios', 'route' => 'scenarios', 'authenticated' => 0, 'parent_id' => 1]); // parent_id=1 renders invisible
		MenuItem::updateOrCreate([ 'key' => 'challenges' ], [ 'title' => 'Challenges', 'route' => 'challenges', 'authenticated' => 0, 'parent_id' => 1]); // parent_id=1 renders invisible
        MenuItem::updateOrCreate([ 'key' => 'about' ], [ 'title' => 'About', 'route' => 'about', 'authenticated' => 0, ]);

        /*
        MenuItem::create([
            'id' => '7', 'title' => 'Datasets', 'url' => '/datasets', 'authenticated' => 2, ]);

        MenuItem::create([
            'id' => '8', 'title' => 'Widgets', 'url' => '/widgets', 'authenticated' => 2, ]);

        MenuItem::create([
            'id' => '9', 'title' => 'Services', 'url' => '/services', 'authenticated' => 2, ]);

        MenuItem::create([
            'id' => '10', 'title' => 'RADAR', 'url' => '/radar', 'authenticated' => 2, ]);*/
    }
}
