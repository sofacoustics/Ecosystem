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

        MenuItem::create([
            'id' => '1', 'title' => 'Data', 'url' => '/databases', 'authenticated' => 0, ]);

        MenuItem::create([
            'id' => '2', 'title' => 'Tools', 'url' => '/tools', 'authenticated' => 0, ]);

        MenuItem::create([
            'id' => '3', 'title' => 'Scenarios', 'route' => 'scenarios', 'authenticated' => 0, ]);

        MenuItem::create([
            'id' => '4', 'title' => 'Challenges', 'route' => 'challenges', 'authenticated' => 0, ]);

        MenuItem::create([
            'id' => '5', 'title' => 'About', 'route' => 'about', 'authenticated' => 0, ]);

        MenuItem::create([
            'id' => '6', 'title' => 'Backend', 'route' => 'filament.admin.pages.dashboard', 'authenticated' => 2, ]);

        MenuItem::create([
            'id' => '7', 'title' => 'Datasets', 'url' => '/datasets', 'authenticated' => 2, ]);

        MenuItem::create([
            'id' => '8', 'title' => 'Widgets', 'url' => '/widgets', 'authenticated' => 2, ]);

        MenuItem::create([
            'id' => '9', 'title' => 'Services', 'url' => '/services', 'authenticated' => 2, ]);

        MenuItem::create([
            'id' => '10', 'title' => 'RADAR', 'url' => '/radar', 'authenticated' => 2, ]);
    }
}
