<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'import']);
        // create permissions
        Permission::create(['name' => 'sale']);
        Permission::create(['name' => 'publish contact']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $role = Role::create(['name' => 'imports']);
        $role->givePermissionTo('import');
        $role = Role::create(['name' => 'sales']);
        $role->givePermissionTo('sale');
        

        // or may be done by chaining
        //$role = Role::create(['name' => 'moderator'])
        //    ->givePermissionTo(['publish articles', 'unpublish articles']);

        $role = Role::create(['name' => 'qa']);
        $role->givePermissionTo(Permission::all());
    }
}
