<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	if (! Role::whereIn('name', config('permission.default_roles'))->count()) {
	    	foreach(config('permission.default_roles') as $role) {
		    	Role::create(['name' => $role]);
	    	}
    	}

    	if (! Permission::whereIn('name', config('permission.default_permissions'))->count()) {
	    	foreach(config('permission.default_permissions') as $permission) {
		    	Permission::create(['name' => $permission]);
	    	}
    	}
    }
}
