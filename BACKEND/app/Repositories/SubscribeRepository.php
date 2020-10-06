<?php
namespace App\Repositories;

use App\Models\Thread;
use App\Models\Subscribe;

class SubscribeRepository
{

	public function subscribe(Thread $thread)
    {
        $user = auth()->user();
        
        $subscribe = $user->subscribes()->where('thread_id', $thread->id)->first();

        if ( is_null($subscribe) ) {
            $thread->subscribes()->create([
                'user_id' => auth()->id()
            ]);

            return true;
        } else {
           $subscribe->delete();

           return false; 
        }
    }

    //
    public function getNotifiableUsers($thread_id)
    {
        return Subscribe::query()->where('thread_id', $thread_id )->pluck('user_id')->toArray();
    }

}