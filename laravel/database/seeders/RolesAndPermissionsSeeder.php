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
        $permission = Permission::create(['name' => 'add datasetdefs']);
        $permission = Permission::create(['name' => 'add databases']);
        $permission = Permission::create(['name' => 'add widget']);
        $permission = Permission::create(['name' => 'edit widget']);

        $contributor = Role::create(['name' => 'contributor']);
        $contributor->givePermissionTo('add databases');
        $contributor->givePermissionTo('add datasetdefs');
        $contributor->givePermissionTo('add datafiletypes');
        $contributor->givePermissionTo('add widget');
        $contributor->givePermissionTo('edit widget');
        $curator = Role::create(['name' => 'curator']);
        $curator->givePermissionTo('add databases');
        $curator->givePermissionTo('add datasetdefs');
        $curator->givePermissionTo('add datafiletypes');
        $curator->givePermissionTo('add widget');
        $curator->givePermissionTo('edit widget');
        $admin = Role::create(['name' => 'admin']);
		$admin->givePermissionTo(Permission::all());
    }
}
