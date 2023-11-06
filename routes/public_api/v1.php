<?php

use App\Http\Controllers\PublicApi\Addresscontroller;
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

Route::get('/', function () {
    dd(\App\Models\User::query()->find(1)->hasRole('customer'));
    return response()->json(
        [
//            'user' => \App\Models\User::query()->find(1)->with('roles')->get(),
//            'token' => \App\Models\User::query()->find(1)->createToken('123')->plainTextToken,
        ]
    );
});

Route::prefix('business - field')->name('bf . ')->group(function () {
    Route::get(' / ', [BusinessFieldController::class, 'list']);
});

Route::prefix('areas')->middleware('cors')->group(function (){
    Route::get('provinces',[Addresscontroller::class,'getProvinces']);
    Route::get('districts /{
        province_id}',[Addresscontroller::class,'getDistricts']);
    Route::get('communes /{
        district_id}',[Addresscontroller::class,'getCommunes']);
});
