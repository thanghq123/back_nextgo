<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\VariationQuantity;
use Illuminate\Http\Request;

class VariationQuantityController extends Controller
{
    public function getVariationQuantity(Request $request){
        try {
            if ($request->inventory_id){
                return responseApi(VariationQuantity::with(['variation','inventory','batch'])->get(),true);
            }
            return responseApi(VariationQuantity::with(['variation','batch'])->get(),true);
        }catch (\Throwable $throwable){
            return responseApi($throwable->getMessage());
        }
    }
}
