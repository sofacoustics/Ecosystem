<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Models\Widget;
use App\Models\Datafiletype;

class DatafiletypeWidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
		//
		// Seed pivot table using 'sync' function.
		//
		// Note: this will remove all other associations
		//
		$datafiletype = Datafiletype::where('key', 'spatial-acoustics-hrtfs-sofa')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-hrtf-general')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-metadata')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'spatial-acoustics-brirs-sofa')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-metadata')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-brir-general')->first()->id => ['is_active' => 0],
			Widget::where('key', 'sofa-brir-listenerview')->first()->id => ['is_active' => 0],
		]);
		$datafiletype = Datafiletype::where('key', 'spatial-acoustics-srirs-sofa')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-metadata')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-srir-general')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'spatial-acoustics-directivities-sofa')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-metadata')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-directivities-polar')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'spatial-acoustics-general-sofa')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-metadata')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'human-and-alike-geometry-non-parametric')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'geometry-mesh')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'human-and-alike-geometry-parametric-csv')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'geometry-bezierppm')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'human-and-alike-image')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'image')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'human-and-human-set-of-photos-animated-webp')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'image')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'environment-spherical-image')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'image')->first()->id => ['is_active' => 1],
			Widget::where('key', 'image-spherical')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'environment-cad-models-any')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'multisensory-explicit-spatial-data-sofa')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-metadata')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-annotated-receiver')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'multisensory-implicit-spatial-data-audio')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'audio')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'non-spatial-acoustic-data-sofa')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-metadata')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-headphones-general')->first()->id => ['is_active' => 1],
			Widget::where('key', 'sofa-selfone')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'non-spatial-audio')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
			Widget::where('key', 'audio')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'non-spatial-table-csv')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'other-any-type')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
		]);
		$datafiletype = Datafiletype::where('key', 'neural-network-pytorch')->first();
		$datafiletype->widgets()->sync([
			Widget::where('key', 'properties')->first()->id => ['is_active' => 1],
		]);
    }
}
