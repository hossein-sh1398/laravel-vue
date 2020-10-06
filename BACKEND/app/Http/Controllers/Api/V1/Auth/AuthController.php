<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Vavidation\VavidationException;
use Auth;
use App\Repositories\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
	//----------  register User  ----------
    public function register(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required',
    		'email' => 'required|email|unique:users,email',
    		'password' => 'required'
    	]);

    	$user = resolve(UserRepository::class)->create($request);

        $defaultSuperAdminEmail = config('permission.default_super_admin_email');
        
        $user->email == $defaultSuperAdminEmail ? $user->assignRole('Super Admins') : $user->assignRole('User');

    	return response()->json([
    		'message' => 'the user created successfully'
    	], Response::HTTP_CREATED);
    }

    //----------  login user  ----------
    public function login(Request $request)
    {
    	$request->validate([
    		'email' => ['required', 'email'],
    		'password' => ['required']
    	]);

    	if (Auth::attempt($request->only(['email', 'password']))) {
    		return response()->json(Auth::user(), Response::HTTP_OK);
    	}

    	throw ValidationException::withMessages([
    		'email' => 'incorrect credentials!'
    	]);
    }

    //----------  logout user  ----------
    public function logout()
    {
    	Auth::logout();

    	return response()->json(['message' => 'logout successfully'], Response::HTTP_OK);
    }

    //---------- get logged user ----------
    public function user()
    {
        $data = [
            'user' => Auth::user(),
            'notifications' => Auth::user()->unReadNotifications
        ];

    	return response()->json($data, Response::HTTP_OK);
    }
}
