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

    public function getVariationQuantityById($id){
        try {
            $variationQuantity = VariationQuantity::with(['variation','batch','variation.product:id,code'])->find($id);
            if (!$variationQuantity){
                return responseApi([],false);
            }
            $data=[
                'id' => $variationQuantity->id,
                'variation_id' => $variationQuantity->variation_id,
                'product_name'=>$variationQuantity->variation->product->name,
                'variation_name' => $variationQuantity->variation->variation_name,
                'batch_id' => $variationQuantity->batch_id??null,
                'batch_name' => $variationQuantity->batch->code??null,
                'quantity' => $variationQuantity->quantity,
                'created_at' => $variationQuantity->created_at,
                'updated_at' => $variationQuantity->updated_at,
            ];
            return responseApi($data,true);
        }catch (\Throwable $throwable){
            return responseApi($throwable->getMessage());
        }
    }
}
