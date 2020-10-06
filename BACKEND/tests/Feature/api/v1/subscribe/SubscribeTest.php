<?php

namespace Tests\Feature\api\v1\subscribe;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\Sanctum;
use App\Models\Thread;
use App\Models\Subscribe;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewReplyThread;

class SubscribeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_create_subscribe_thread()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        
        $thread = Thread::factory()->create();

        $response = $this->get(route('subscribe', $thread->id));

        $this->assertEquals( 1, $thread->subscribes()->count() );
        
        $this->assertTrue( !!$thread->subscribes()->count() );
        
        $response->assertStatus( Response::HTTP_CREATED );

        $response->assertJson([
            'message' => 'subscribe created successfully'
        ]);
    }

    //
    public function test_delete_subscribe_thread()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);
        
        $thread = Thread::factory()->create();

        $thread->subscribes()->create([
            'user_id' => $user->id
        ]);

        $response = $this->get(route('subscribe', $thread->id));
        
        $this
            ->assertEquals( 0, $thread->subscribes()->count() );
        
        $this
            ->assertFalse( !!$thread->subscribes()->count() );
        
        $response
            ->assertStatus( Response::HTTP_OK );

        $response->assertJson([
            'message' => 'subscribe deleted successfully'
        ]);
    }

    //
    public function test_notificatioin_thread()
    {
        $this->withoutExceptionHandling();
        Notification::fake();

        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $thread = Thread::factory()->create();

        Subscribe::factory()->state([
            'user_id' => $user->id,
            'thread_id' => $thread->id
        ])->create();

        $response = $this->postJson(route('answer.store'), [
            'thread_id' => $thread->id,
            'content' => 'Foo Bar'
        ]);

        Notification::assertSentTo($user, NewReplyThread::class);
    }
}
