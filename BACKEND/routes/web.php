<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $user = App\Models\User::find(13);
    // 	'name' => 'hossein shirinegad',
    // 	'email' => 'hosseinshirinegad98@gmail.com',
    // 	'password' => \Hash::make('password')
    // ]);
    //  'user_id' => $user->id
    // ])->create();
    //$user->notify(new App\Notifications\NewReplyThread($thread));
    // $data = json_decode($user->unReadNotifications[1]->data, true);
    // dd($user->unReadNotifications[1]->data['title_thread']);
    // dd($data);
    // $thread = App\Models\Thread::first();
    // dd($thread->subscribes()->pluck('user_id')->toArray());
    //  $data = $request->only( 
    //         [ 'content', 'thread_id' ] 
    //     );
        auth()->loginUsingId(13);
        $answer = auth()->user()
            ->answers()
            ->save( 
                new \App\Models\Answer([
                    'thread_id' => 8,
                    'content' => 'Foo'
                ]) 
            );
        dd($answer);
});