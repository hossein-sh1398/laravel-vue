<?php
namespace App\Repositories;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerRepository
{
    /*
    * get all list answers from database
    */
	public function index()
    {
        return Answer::query()->latest()->get();
    }

    //
    public function store(Request $request)
    {
        $data = $request->only( [ 'content', 'thread_id' ] );
        
        auth()->user()
            ->answers()
            ->save(  new Answer($data) );
    }

    //
    public function update($content, Answer $answer)
    {
        $answer->update( ['content' => $content] );
    }

    //
    public function delete(Answer $answer)
    {
        $answer->delete();
    }
}