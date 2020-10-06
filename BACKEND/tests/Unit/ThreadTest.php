<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Thread;
use App\Models\Channel;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ThreadTest extends TestCase
{
	use RefreshDatabase;

    /*
    *
    */
    public function test_index()
    {
        $this->withoutExceptionHandling();

        $response = $this->get(route('thread.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /*
    *
    */
    public function test_show()
    {
        $thread = Thread::factory()->create();
        $response = $this->get(route('thread.show', $thread->slug));

        $response->assertStatus(Response::HTTP_OK);
    }

    //
    public function test_validate_store_thread()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('thread.store'));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    //
    public function test_store_thread()
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson(route('thread.store'), [
            'title' => 'this is a title thread',
            'content' => 'this is a content thread',
            'channel_id' => Channel::factory()->create()->id,
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    //
    public function test_validate_update_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory()->state(['user_id' => $user->id])->create();
        $response = $this->json('PUT', route('thread.update', $thread->id));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

     //
    public function test_update_can_authorize_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->create();
        $response = $this->putJson(route('thread.update', $thread->id), [
            'title' => 'new title',
            'content' => 'this is a content thread',
            'channel_id' => Channel::factory()->create()->id
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    //
    public function test_update_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->state(['user_id' => $user->id])->create();

        $response = $this->putJson(route('thread.update', $thread->id), [
            'title' => 'new title',
            'content' => 'this is a content thread',
            'channel_id' => Channel::factory()->create()->id
        ])->assertSuccessful();

        $thread->refresh();
        $response->assertStatus(Response::HTTP_OK);
       $this->assertSame('new title', $thread->title);
    }

    //
    public function test_best_answer_id_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->state(['user_id' => $user->id])->create();

        $response = $this->putJson( route( 'thread.update', $thread->id ), [
            'answer_id' => 1
        ] )->assertSuccessful();

        $thread->refresh();

        $this->assertEquals('1', $thread->answer_id);
    }

       //
    public function test_can_user_destroy_thread()
    {
        Sanctum::actingAs(User::factory()->create());
        $thread = Thread::factory()->create();
        $response = $this->delete(route('thread.destroy', $thread->id) );
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    //
    public function test_destroy_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $thread = Thread::factory()->state(['user_id' => $user->id])->create();
        $response = $this->delete(route('thread.destroy', $thread->id) );
        $response->assertStatus(Response::HTTP_OK);
    }

}