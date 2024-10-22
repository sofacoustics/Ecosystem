<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Data\RadardatasetpureData;

use App\Models\MenuItem;
use App\Models\Database;
use App\Models\Radardatasetrightsholder;


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
        Database::create(array('title' => 'ARI B', 
            'description' => 'Specialty: hrtfs, dtfs, and photos of the ears', 
            'radardataset' => RadardatasetpureData::from('{
                        "title": "ARI B RADAR dataset",
                                        "publishers": {
                                                        "publisher": [
                                                                                {
                                                                                                            "value": "OEAW",
                                                                                                                                        "nameIdentifierScheme": "OTHER",
                                                                                                                                                                "schemeURI": null,
                                                                                                                                                                                        "nameIdentifier": null
                                                                                                                                                                                                            },
                                                                                                                                                                                                                                    {
                                                                                                                                                                                                                                                                "value": "TU Wien",
                                                                                                                                                                                                                                                                                            "nameIdentifierScheme": "OTHER",
                                                                                                                                                                                                                                                                                                                    "schemeURI": null,
                                                                                                                                                                                                                                                                                                                                            "nameIdentifier": null
                                                                                                                                                                                                                                                                                                                                                                }
                    ]
                                        }
        }'),
            'user_id' => 1));
        Database::create(array('title' => 'ARI BezierPPM', 
            'description' => 'Specialty: csv, dtfs and hrtfs', 
            'radardataset' => RadardatasetpureData::from('{
                        "title": "ARI B RADAR dataset",
                                        "publishers": {
                                                        "publisher": [
                                                                                {
                                                                                                            "value": "OEAW",
                                                                                                                                        "nameIdentifierScheme": "OTHER",
                                                                                                                                                                "schemeURI": null,
                                                                                                                                                                                        "nameIdentifier": null
                                                                                                                                                                                                            },
                                                                                                                                                                                                                                    {
                                                                                                                                                                                                                                                                "value": "TU Wien",
                                                                                                                                                                                                                                                                                            "nameIdentifierScheme": "OTHER",
                                                                                                                                                                                                                                                                                                                    "schemeURI": null,
                                                                                                                                                                                                                                                                                                                                            "nameIdentifier": null
                                                                                                                                                                                                                                                                                                                                                                }
                    ]
                                        }
        }'),
            'user_id' => 1, ));
        //Database::create(array('name' => 'Jonnie\'s ARI SOFA test 2', 'description' => 'Nr. 2. \'Nuf said!', 'user_id' => 1, 'radar_id' => 'dEZxRRrxpiHSzbBZ'));
        $this->call([
            DatasetSeeder::class,
            DatafiletypeSeeder::class,
            ToolSeeder::class,
            DatasetdefSeeder::class,
            DatafileSeeder::class,
            MenuItemSeeder::class,
            RadardatasetresourcetypeSeeder::class,
            RadardatasetsubjectareaSeeder::class,
            RadardatasetrightsholderSeeder::class,
            RadardatasetSeeder::class,
        ]);

    }
}
