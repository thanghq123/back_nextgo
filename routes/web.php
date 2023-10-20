<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\BusinessFieldController;

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
Route::get('/mail',[TestController::class,'index']);
Route::get('/create',[TestController::class,'create']);
Route::get('/list',[TestController::class,'list']);
Route::post('/upload',[TestController::class,'upload'])->name('upload');
Route::get('/rename',[TestController::class,'rename']);
Route::get('/delete',[TestController::class,'delete']);

Route::prefix('admin')->name('admin.')->group(function (){
    Route::view('/','admin.dashboard.index')->name('home');
    Route::prefix('business-field')->name('bf.')->group(function (){
        Route::get('/',[BusinessFieldController::class,'index'])->name('index');
        Route::post('store',[BusinessFieldController::class,'store'])->name('create');
        Route::get('show',[BusinessFieldController::class,'show'])->name('show');
        Route::put('update',[BusinessFieldController::class,'update'])->name('update');
        Route::delete('delete',[BusinessFieldController::class,'delete'])->name('delete');
    });
    Route::prefix('pricing')->name('pricing.')->group(function (){
        Route::get('/',[PricingController::class,'index'])->name('index');
        Route::post('/store',[PricingController::class,'store'])->name('store');
        Route::get('/show',[PricingController::class,'show'])->name('show');
        Route::put('/update',[PricingController::class,'update'])->name('update');
        Route::delete('/delete',[PricingController::class,'delete'])->name('delete');
    });
});
