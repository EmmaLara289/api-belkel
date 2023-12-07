<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('verify.user.jwt')->group(function () {
    //return $request->user();

    Route::post('/createBanner', [UserController::class, 'createBanner']);
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::post('/updateUser', [UserController::class, 'updateUser']);
    Route::post('/createMain', [UserController::class, 'createMain']);
    Route::get('/getMain', [UserController::class, 'getMain']);
    Route::get('/getBanner', [UserController::class, 'getBanner']);
        
    
    
    

    
});


Route::post('/login', [UserController::class, 'login']);
