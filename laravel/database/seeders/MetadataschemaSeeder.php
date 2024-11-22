<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Radar\Metadataschema;

class MetadataschemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'Audiovisual'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'Collection'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'Dataset'));
        Metadataschema::create(array('version' => '9.1', 'type' => 'controlledlist', 'name' => 'resourceType', 'value' => 'Event'));
    }
}
