<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\UserController;

/*
* Route Group Authentication User
*/
Route::prefix('users')->group(function() {
	Route::get(
		'leaderboards', 
		[UserController::class, 'leaderboards']
	)->name('user.leaderboards');
});