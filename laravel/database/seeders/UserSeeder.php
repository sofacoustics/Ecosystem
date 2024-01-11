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
        //
        $user = User::create([
            'name' => 'Jonnie Stuefer',
            'email' => 'jonnie@floorspot.org',
            'password' => '$2y$10$eljLQEcv5TU/7sgcBQZCMupOVkHPKIrPPXOgKmSs/MKlI9i.Mnp66'
           ]);
           $user->assignRole('admin');

    }
}
