<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

//use App\Data\RadardatasetpureData;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		// This data is static and is not modified by the users
		$this->call(StaticSeeder::class);

		if(app()->environment(['local', 'testing'])) {
			$this->call([
				Test\UserSeeder::class,
				Test\DatabaseSeeder::class,
				Test\DatasetSeeder::class,
				Test\DatasetdefSeeder::class,
				Test\ToolSeeder::class,
				Test\CreatorSeeder::class,
				Test\PublisherSeeder::class,
				Test\SubjectAreaSeeder::class,
				Test\RightsholderSeeder::class,
				Test\KeywordSeeder::class,
				Test\RelatedIdentifierSeeder::class,
				Test\CommentSeeder::class,
			]);
		}
	}
}
