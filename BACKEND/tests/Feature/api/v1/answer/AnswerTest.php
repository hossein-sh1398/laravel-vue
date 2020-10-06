<?php

namespace Tests\Feature\api\v1\answer;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\Sanctum;
use App\Models\Thread;
use App\Models\Answer;
use App\Models\Subscribe;

class AnswerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_answer()
    {
        $response = $this->get( route('answer.index') );

        $response->assertSuccessful();
    }

    // test for answer validate
    public function test_validate_answer_thread()
    {
        Sanctum::actingAs( User::factory()->create() );

        $response = $this->postJson( route('answer.store') );

        $response->assertStatus( Response::HTTP_UNPROCESSABLE_ENTITY );

        $response->assertJsonValidationErrors( ['content', 'thread_id'] );
    }

    // test store answer
    public function test_store_answer_thread()
    {
        Sanctum::actingAs( User::factory()->create() );

        $thread = Thread::factory()->create();

        $response = $this->postJson( 
            route('answer.store'), 
            [
                'content' => 'Foo',
                'thread_id' => $thread->id
            ]
        );
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertTrue( $thread->answers()->where('content', 'Foo')->exists() );
    }

    // test for answer update validate
    public function test_validate_update_answer_for_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $answer = Answer::factory()->create();
        $response = $this->putJson( route( 'answer.update', $answer->id ) );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

     // test store answer
    public function test_update_answer_thread()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $answer = Answer::factory()->create([
            'user_id' => $user->id
        ]);
        $response = $this->putJson(
            route('answer.update', $answer->id), 
            [
                'content' => 'hossein',
            ]
        )->assertSuccessful();

        $answer->refresh();
        $this->assertEquals('hossein', $answer->content);
    }

    //
    public function test_authorize_update_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);
        $answer = Answer::factory()->create();
        $response = $this->putJson(route('answer.update', $answer->id), [
            'content' => 'hossein',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }
    
    //
    public function test_delete_answer()
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $answer = Answer::factory()->create([
            'user_id' => $user->id,
            'content' => 'Foo'
        ]);

        $response = $this->delete( route('answer.destroy', $answer->id) );

        $response->assertJson([
            'message' => 'the answer deleted successfully'
        ]);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertFalse( !! Answer::find($answer->id) );
    }

    //
    public function test_authorize_delete_answer()
    {
        Sanctum::actingAs( User::factory()->create() );
        $answer = Answer::factory()->create();
        $response = $this->delete( route('answer.destroy', $answer->id) );
        $response->assertJson( ['message' => 'access denidde'] );
        $response->assertStatus( Response::HTTP_FORBIDDEN );
    }

    // test store answer
    public function test_score_for_user_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs( $user );

        $thread = Thread::factory()->create();

        $response = $this->postJson( 
            route('answer.store'), 
            [
                'content' => 'Title',
                'thread_id' => $thread->id
            ]
        );
        $response->assertStatus(Response::HTTP_CREATED);
        $user->refresh();
        $this->assertEquals(10, $user->score);
    }

    // test  score user
    public function test_unscore_for_user_answer()
    {
        $user = User::factory()->create();
        Sanctum::actingAs( $user );

        $thread = Thread::factory()->state([
            'user_id' => $user->id
        ])->create();

        $response = $this->postJson( 
            route('answer.store'), 
            [
                'content' => 'Title',
                'thread_id' => $thread->id
            ]
        );
        $response->assertStatus(Response::HTTP_CREATED);
        $user->refresh();
        $this->assertEquals(0, $user->score);
    }

}