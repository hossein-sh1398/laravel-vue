<?php

namespace App\Http\Controllers\Api\V1\Thread;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Thread;
use App\Repositories\ThreadRepository;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $threads = resolve(ThreadRepository::class)->index();

        return response()->json($threads, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

        resolve(ThreadRepository::class)->store($request);
        
        return response()->json([
            'message' => 'the thread created successfully'
        ], Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $thread = resolve(ThreadRepository::class)->show($slug);

        return response()->json($thread, Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        if ( $request->has('answer_id') ) {
            $this->validate($request, ['answer_id' => 'required']);
        } else {
            $request->validate([
                'title' => 'required',
                'content' => 'required',
                'channel_id' => 'required|exists:channels,id'
            ]);
        }
        if (\Gate::allows('user-thread', $thread)) {
            resolve(ThreadRepository::class)->update($request, $thread);

            return response()->json([
                'message' => 'the thread updated successfully'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'message' => 'the thread cant update'
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        if (\Gate::allows('user-thread', $thread)) {
            resolve(ThreadRepository::class)->delete($thread);
            
            return response()->json([
                'message' => 'the thread deleted successfully'
            ], Response::HTTP_OK);
        }
            
        return response()->json([
            'message' => 'you forbidden'
        ], Response::HTTP_FORBIDDEN);
    }
}
