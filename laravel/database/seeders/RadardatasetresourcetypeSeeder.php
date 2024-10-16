<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon;

use App\Models\Radardatasetresourcetype;

class RadardatasetresourcetypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hard-coded possible values will be defined elsewhere - maybe in view?
        Radardatasetresourcetype::create(array('resource_type' => 'Other', 'value' => 'Acoustics', 'radardataset_id' => 1, 'created_at' => Carbon\CarbonImmutable::now(), 'updated_at' => Carbon\CarbonImmutable::now()));
        Radardatasetresourcetype::create(array('resource_type' => 'Other', 'value' => 'Acoustics', 'radardataset_id' => 2, 'created_at' => Carbon\CarbonImmutable::now(), 'updated_at' => Carbon\CarbonImmutable::now()));
    }
}
