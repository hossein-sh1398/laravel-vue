<?php

namespace App\Http\Controllers\Api\V1\Thread;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AnswerRepository;
use App\Repositories\SubscribeRepository;
use App\Repositories\UserRepository;
use App\Repositories\ThreadRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Answer;
use Gate;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewReplyThread;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answers = resolve(AnswerRepository::class)->index();

        return response()->json($answers, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = \Validator::make($request->all(), [
            'content' => 'required',
            'thread_id' => 'required'
        ]);

        if ( $result->fails() ) {
            return response()->json(
                ['errors' => $result->errors()], 
                Response::HTTP_UNPROCESSABLE_ENTITY
            );    
        }

        resolve(AnswerRepository::class)->store($request);

        $notifiable_user_ids = resolve(SubscribeRepository::class)->getNotifiableUsers($request['thread_id']);
        
        $notifiable_users = resolve(UserRepository::class)->find($notifiable_user_ids);

        $thread = resolve(ThreadRepository::class)->find( $request['thread_id'] );

        Notification::send( $notifiable_users, new NewReplyThread($thread) );

        $user = auth()->user();
        if ( ! $user->threads->contains($thread) ) {
            $user->increment('score', 10);
        }
            
        
        return response()->json(
            ['message' => 'answer created successfully'], 
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Answer $answer)
    {
        $result = \Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ( $result->fails() ) {
            return response()->json(
                ['errors' => $result->errors()], 
                Response::HTTP_UNPROCESSABLE_ENTITY
            );    
        }

        if (\Gate::allows('user-answer', $answer) ) {
            resolve(AnswerRepository::class)->update($request->content, $answer);
            return response()->json(
                ['message' => 'answer updated successfully'], 
                Response::HTTP_OK
            );
        } else {
            return response()->json(
                ['message' => 'access denidde'], 
                Response::HTTP_FORBIDDEN
            );   
        }
    }
            

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Answer $answer )
    {
        if ( \Gate::allows( 'user-answer', $answer ) ) {

            resolve( AnswerRepository::class )->delete($answer);

            return response()->json(
                [
                    'message' => 'the answer deleted successfully'
                ], 
                Response::HTTP_OK
            );

        } else {
            return response()->json(
                ['message' => 'access denidde'], 
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
