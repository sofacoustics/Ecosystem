<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // https://spatie.be/docs/laravel-permission/v6/advanced-usage/seeding
        //
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


				$permission = Permission::create(['name' => 'add datafiletypes']);

        $role = Role::create(['name' => 'curator']);
        $role = Role::create(['name' => 'admin']);
				$role->givePermissionTo(Permission::all());


    }
}
