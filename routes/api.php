<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\userController;
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

Route::post('/signup',[Authcontroller::class,"Signup"]);
Route::post('/login',[Authcontroller::class,"Login"]);
Route::get('/one',function(){
    return response()->json(['one'=>"test"]);
});
Route::middleware('auth:sanctum')->group(function(){
    Route::post("/edituserdata",[userController::class,"editUserData"]);
    Route::post('/editpassword',[userController::class,"editPassword"]);
    Route::get("/userdetails",[userController::class,"userDetails"]);
    Route::get('/logout',[Authcontroller::class,"Logout"]);
    Route::post("/updateimage",[userController::class,"updateImage"]);
    Route::get("/items",[ItemController::class,'items']);
    Route::post("/additem",[ItemController::class,'additem']);
    Route::get("/checkedtrigger/{id}",[ItemController::class,'checkedTrigger']);
    Route::delete("/deleteitem/{id}",[ItemController::class,'deleteitem']);

});
