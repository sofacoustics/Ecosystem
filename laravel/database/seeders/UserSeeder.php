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
            'password' => '$2y$10$eljLQEcv5TU/7sgcBQZCMupOVkHPKIrPPXOgKmSs/MKlI9i.Mnp66'
           ]);
           $user->assignRole('admin');
        $user = User::create([
            'name' => 'Piotr Admin',
            'email' => 'piotr.majdak@oeaw.ac.at',
            'password' => '$2y$10$0cANSNliDXf8DGq2wSX4x..DT2jHwMmsuBx.Q05KcJ2vy8OW.NSIO'
           ]);
           $user->assignRole('admin');
        $user = User::create([
            'name' => 'Jonnie User',
            'email' => 'jonathan.stuefer@oeaw.ac.at',
            'password' => '$2y$10$eljLQEcv5TU/7sgcBQZCMupOVkHPKIrPPXOgKmSs/MKlI9i.Mnp66'
           ]);
    }
}
