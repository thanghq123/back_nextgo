<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
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
    return view('welcome');
});

Route::get('/test',[TestController::class,'test']);
Route::get('/mail',[TestController::class,'index']);
Route::get('/create',[TestController::class,'create']);
Route::get('/list',[TestController::class,'list']);
Route::post('/upload',[TestController::class,'upload'])->name('upload');
Route::get('/rename',[TestController::class,'rename']);
Route::get('/delete',[TestController::class,'delete']);
