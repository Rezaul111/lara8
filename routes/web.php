<?php

use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

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
    return view('user');

});
Route::post('new-user',[TestController::class,'store']);
Route::get('get-all-users',[TestController::class,'getAll']);
Route::get('edit-user/{id}',[TestController::class,'edit']);
Route::post('update-user',[TestController::class,'update']);
Route::get('delete-user/{id}',[TestController::class,'delete']);

