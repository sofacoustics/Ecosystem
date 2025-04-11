<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
			// Possibly create a hashed password using php artisan tinker
			// Then write echo Hash::make('somestring');
        $user = User::create([
            'name' => 'Jonnie Admin',
            'email' => 'jonnie@floorspot.org',
            'password' => '$2y$10$eljLQEcv5TU/7sgcBQZCMupOVkHPKIrPPXOgKmSs/MKlI9i.Mnp66',
						'orcid' => '0000-0000-0000-0000'
           ]);
           $user->assignRole('admin');
					 
        $user = User::create([
            'name' => 'Piotr Majdak',
            'email' => 'piotr.majdak@oeaw.ac.at',
            'password' => '$2y$10$oO3j2Rv8E5qlsFlEWZMbk..qpiEcABAQkFATlXeHI.ZN/xI2LaDL.',
						'orcid' => '0000-0003-1511-6164'
           ]);
           $user->assignRole('admin');
					 
        $user = User::create([
            'name' => 'Jonnie User',
            'email' => 'jonathan.stuefer@oeaw.ac.at',
            'password' => '$2y$10$eljLQEcv5TU/7sgcBQZCMupOVkHPKIrPPXOgKmSs/MKlI9i.Mnp66',
						'orcid' => '0000-0000-0000-0001'
        ]);
				
        $user = User::create([
            'name' => 'Michael Mihocic',
            'email' => 'michael.mihocic@oeaw.ac.at',
            'password' => '$2y$10$en9XzixWc7wEn25LoZOn8.M7DYodeQa7T2najcOp1h5B/OCMiPKS6',
						'orcid' => '0000-0002-0800-6293'
        ]);
           $user->assignRole('admin');

    }
}
