<?php

namespace App\Http\Controllers\api\v1\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function userNotifications()
    {
    	return response()->json([
    		auth()->user()
    			->unReadNotifications;
    	], Response::HTTP_OK);
    }

    //
    public function leaderboards()
    {
    	return resolve(UserRepository::class)->leaderboards(); 
    }
}
