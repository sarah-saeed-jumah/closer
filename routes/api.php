<?php

use App\Events\SendLocation;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

    });

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::post('/logout',[ UserController::class, 'logout'] );
    Route::post('/store',[MessageController::class,'storeMessage'] );
    Route::post('/message/{id}',[MessageController::class,'getMessagesAuthToUser'] );
    Route::post('/read/message/{id}', [MessageController::class,'readMessage']);
    Route::post('/read/messages/{id}',[MessageController::class,'readMessages']);
    Route::post('messages',[MessageController::class,'getMessagesAuth']);

    Route::post('resetLocation', [LocationController::class, 'setLoc']);
    Route::post('test', [LocationController::class, 'getauth']);


});


// Route::get('getLoc',[LocationController::class],'getLocation');
Route::post('/register',[UserController::class, 'register'] );
Route::post('/login',[ UserController::class, 'login']);

  Route::get('/getLoc',[LocationController::class,'getLocation'] );

  Route::post('setLocation', [LocationController::class, 'setLocation']);
   Route::get('/getauth',[MessageController::class,'getauth'] );

  Route::post('/map', function (Request $request) {
    $lat = $request->input('lat');
    $long = $request->input('long');
    $location = ["lat"=>$lat, "long"=>$long];
    event(new  SendLocation($location));
    return response()->json(['status'=>'success', 'data'=>$location]);
});
