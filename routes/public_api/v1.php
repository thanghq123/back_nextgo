<?php

use App\Http\Controllers\PublicApi\Addresscontroller;
use App\Http\Controllers\PublicApi\BusinessFieldController;
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
    Route::post('create',[BusinessFieldController::class,'create'])
        ->name('create');
    Route::post('update/{id}',[BusinessFieldController::class,'update'])
        ->name('update');
    Route::get('delete/{id}',[BusinessFieldController::class,'delete'])
        ->name('delete');
});

Route::prefix('areas')->middleware('cors')->group(function (){
    Route::get('provinces',[Addresscontroller::class,'getProvinces']);
    Route::get('districts/{province_id}',[Addresscontroller::class,'getDistricts']);
    Route::get('communes/{district_id}',[Addresscontroller::class,'getCommunes']);
});
