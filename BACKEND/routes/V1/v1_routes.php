<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
	
	/*
	* Route Group Authentication User
	*/
	require __DIR__.'/authenticate_routes.php';

	/*
	*Route Cannels
	*/
	require __DIR__.'/channel_routes.php';

	/*
	*Route Threads
	*/
	require __DIR__.'/thread_routes.php';	

	/*
	*Route leaderboards
	*/
	require __DIR__.'/leaderboards_routes.php';

});