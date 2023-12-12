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

    
    //crud users
    Route::post('/register', [UserController::class, 'register']);
    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::post('/updateUser', [UserController::class, 'updateUser']);
    Route::post('/deleteUser', [UserController::class, 'deleteUser']);
    
    //crud main
    Route::post('/createMain', [UserController::class, 'createMain']);
    Route::post('/updateMain', [UserController::class, 'updateMain']);    
    Route::post('/deleteMain', [UserController::class, 'deleteMain']);    
    Route::get('/getMain', [UserController::class, 'getMain']);

    //crud banner
    Route::post('/createBanner', [UserController::class, 'createBanner']);
    Route::post('/deleteBanner', [UserController::class, 'deleteBanner']);
    Route::post('/updateBanner', [UserController::class, 'updateBanner']);
    Route::get('/getBanner', [UserController::class, 'getBanner']);
    
    //disables
    Route::post('/disableUser', [UserController::class, 'disableUser']);
    Route::post('/disableMain', [UserController::class, 'disableMain']);
    Route::post('/disableBanner', [UserController::class, 'disableBanner']);

    //gets disables
    Route::get('/getUsersDisable', [UserController::class, 'getUsersDisable']);
    Route::get('/getBannersDisable', [UserController::class, 'getBannersDisable']);
    Route::get('/getMainsDisable', [UserController::class, 'getMainsDisable']);
    
    

    
});
    //Route::post('/register', [UserController::class, 'register']);

    Route::post('/login', [UserController::class, 'login']);
