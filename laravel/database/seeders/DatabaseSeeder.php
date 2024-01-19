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
        Database::create(array('name' => 'ARI (LAS)', 'description' => 'n-the-ear HRTFs and DTFs are measured in the ARI loudspeaker array studio (LAS). Each set of HRTFs contains 451 sound-source directions (91 loudspeakers by 5 subject roations), see McLachlan et al. (2023)', 'user_id' => 1));
        Database::create(array('name' => 'CHEDAR', 'description' => 'Numerically calculated HRTFs (.sofa) with 3D meshes of the head and pinnae (.ply) and anthropometric data (.mat) provided. For more details, see the documentation. (Credit: Slim Ghorbal, France)', 'user_id' => 1));
        Database::create(array('name' => 'Jonnie\'s ARI SOFA test 2', 'description' => 'Nr. 2. \'Nuf said!', 'user_id' => 1, 'radar_id' => 'dEZxRRrxpiHSzbBZ'));
				$this->call([
					DatasetSeeder::class,
					DatafiletypeSeeder::class,
					DatafileSeeder::class,
					MenuItemSeeder::class,
				]);

    }
}
