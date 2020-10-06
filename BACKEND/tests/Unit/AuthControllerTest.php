<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthControllerTest extends TestCase
{

	use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_validate_register()
    {
    	$response = $this->postJson(route('auth.register'));
       
        $response->assertStatus(422);
    }

    /*
    *
    */
    protected function makeRolePermission()
    {
        if (! Role::whereIn( 'name', config('permission.default_roles') )->count() ) {
            foreach(config('permission.default_roles') as $role) {
                Role::create(['name' => $role]);
            }
        }

        if (! Permission::whereIn( 'name', config('permission.default_permissions') )->count() ) {
            foreach(config('permission.default_permissions') as $permission) {
                Permission::create(['name' => $permission]);
            }
        }
    }

    /**
    *
    */
    public function test_insert_user()
    {
    	$this->withoutExceptionHandling();
        $this->makeRolePermission();

    	$response = $this->postJson('api/v1/auth/register', [
    		'name' => 'hosseinf',
    		'email' => 'hosseinskhirinegad98f@gmail.com',
    		'password' => '1234567890'
    	]);

    	$response->assertStatus(201);
    }

    public function test_validate_login()
    {
    	$response = $this->postJson(route('auth.login'));
       
        $response->assertStatus(422);
    }

    public function test_user_login()
    {
    	$user = User::factory()->create();

    	$response = $this->postJson(route('auth.login'), [
    		'email' => $user->email,
    		'password' => 'password'
    	]);

    	$response->assertStatus(200);
    }

    //logout test
    public function test_logout_user()
    {
    	$user = User::factory()->create();
    	$response = $this->actingAs($user)->postJson(route('auth.logout'));

    	$response->assertStatus(200);
    }

    //get logged user
    public function test_user()
    {
    	$user = User::factory()->create();
    	$response = $this->actingAs($user)->get(route('auth.user'));
    	$response->assertStatus(200);
    }
}
