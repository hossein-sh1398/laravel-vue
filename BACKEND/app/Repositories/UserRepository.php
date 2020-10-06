<?php
namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
	public function create(Request $request)
    {
    	return User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => Hash::make($request->password)
    	]);
    }

    public function find($user_ids)
    {
    	return User::find($user_ids);
    }

    //
    public function leaderboards() 
    {
        return User::orderByDesc('score')->paginate(20);
    }
}