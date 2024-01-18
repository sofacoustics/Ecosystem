<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\Database;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com', 
        // ]);
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        Database::create(array('name' => 'A local database', 'description' => 'A truly unique database, I assure you!', 'user_id' => 1));
        Database::create(array('name' => 'Jonnie\'s ARI SOFA test 1', 'description' => 'Not much to say about this database.', 'user_id' => 1, 'radar_id' => 'iqcCQbvmGzYxYUne'));
        Database::create(array('name' => 'Jonnie\'s ARI SOFA test 2', 'description' => 'Nr. 2. \'Nuf said!', 'user_id' => 1, 'radar_id' => 'dEZxRRrxpiHSzbBZ'));
				$this->call([
					DatasetSeeder::class,
					DatafileSeeder::class,
					DatafiletypeSeeder::class,
					MenuItemSeeder::class,
				]);

    }
}
