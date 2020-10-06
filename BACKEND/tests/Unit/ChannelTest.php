<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Channel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class ChannelTest extends TestCase
{
	use RefreshDatabase;

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
    
    /*
    *
    */
    public function test_all()
    {
        $this->withoutExceptionHandling();

        $this->makeRolePermission();
        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        $response = $this->actingAs($user)->get(route('channel.all'));

        $response->assertStatus(Response::HTTP_OK);
    }


    /*
    *
    */
    public function gtest_validate_channel()
    {
    	$this->withoutExceptionHandling();
    	$response = $this->postJson(route('channel.create'));
       
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    /*
    *
    */
    public function test_channel_create()
    {
    	$this->withoutExceptionHandling();

        $this->makeRolePermission();
        $user = User::factory()->create();
        $user->givePermissionTo('channel management');

    	$response = $this->actingAs($user)->postJson('api/v1/channel', [
    		'name' => Str::random(10)
    	]);

    	$response->assertStatus(Response::HTTP_CREATED);
    }

    /*
    *
    */
    public function test_validate_update()
    {
        $this->makeRolePermission();
        $user = User::factory()->create();
        $user->givePermissionTo('channel management');

        $response = $this->actingAs($user)->json('PUT', route('channel.update'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /*
    *
    */
    public function test_update_channel()
    {
        $this->makeRolePermission();
        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        
        $channel = Channel::factory()
            ->state([
                'name' => 'linux'
            ])->create();

        $response = $this->actingAs($user)->json('PUT', route('channel.update'), [
            'id' => $channel->id,
            'name' => 'mac'
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEquals('mac', Channel::find($channel->id)->name);
    }

    /*
    *
    */
    public function test_delete_channel()
    {
        $this->makeRolePermission();
        $user = User::factory()->create();
        $user->givePermissionTo('channel management');
        
        $channel = Channel::factory(['name' => 'angular'])->create();
        $response = $this->actingAs($user)->json('DELETE', route('channel.destroy', $channel->id));

        $response->assertStatus(Response::HTTP_OK);
    }

}
