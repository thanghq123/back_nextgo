<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicApi\Addresscontroller;
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

Route::get('/', function (Request $request) {
    return 1;
});

Route::prefix('areas')->middleware('cors')->group(function (){
    Route::get('provinces',[Addresscontroller::class,'getProvinces']);
    Route::get('districts/{province_id}',[Addresscontroller::class,'getDistricts']);
    Route::get('communes/{district_id}',[Addresscontroller::class,'getCommunes']);
});
