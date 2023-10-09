<?php

use App\Http\Controllers\BusinessFieldController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/',function (){
    return 1;
});
Route::prefix('business-field')->name('bf.')->group(function () {
    Route::get('/', [BusinessFieldController::class, 'list'])
        ->name('list');
    Route::get('show/{id}',[BusinessFieldController::class,'getById'])
        ->name('get-by-id');
    Route::match(['get','post'],'create',[BusinessFieldController::class,'create'])
        ->name('create');
    Route::match(['get','post'],'update/{id}',[BusinessFieldController::class,'update'])
        ->name('update');
    Route::get('delete/{id}',[BusinessFieldController::class,'delete'])
        ->name('delete');
});

