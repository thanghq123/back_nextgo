<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\VariationQuantity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VariationQuantityController extends Controller
{
    /**
     * @path /tenant/api/v1/storage/get-variation-inventory
     * @desciption danh sách đơn nhập kho
     * @method POST
     * @param Request $request inventory_id||null
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function getVariationQuantity(Request $request)
    {
        try {
            $data = VariationQuantity::with(['variation', 'inventory', 'batch'])->paginate(10);
            if ($request->has('inventory_id') && $request->inventory_id!="") {
                $data = VariationQuantity::with(['variation', 'inventory', 'batch'])->where('inventory_id', $request->inventory_id)->paginate(10);
            }
            $result = $data->getCollection()->transform(function ($data) {
                return [
                    'id' => $data->id,
                    'inventory_id' => $data->inventory_id,
                    'inventory_name' => $data->inventory->name,
                    'variation_id' => $data->variation_id,
                    'variation_name' => $data->variation->variation_name,
                    'sku' => $data->variation->sku??null,
                    'barcode' => $data->variation->barcode??null,
                    'price_import' => $data->variation->price_import??null,
                    'price_export' => $data->variation->price_export??null,
                    'product_id' => $data->variation->product_id,
                    'product_name' => $data->variation->product->name,
                    'batch_id' => $data->batch_id??null,
                    'batch_code' => $data->batch->code??null,
                    'quantity' => $data->quantity,
                    'created_at' => Carbon::make($data->created_at)->format('Y-m-d'),
                    'updated_at' => $data->updated_at,
                ];
            });
            return responseApi(paginateCustom($result,$data), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }
    /**
     * @path /tenant/api/v1/storage/get-variation/{id}
     * @desciption danh sách đơn nhập kho
     * @method POST
     * @param Request $request id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function getVariationQuantityById($id)
    {
        try {
            $variationQuantity = VariationQuantity::with(['variation', 'batch', 'variation.product:id,code'])->find($id);
            if (!$variationQuantity) {
                return responseApi([], false);
            }
            $data = [
                'id' => $variationQuantity->id,
                'variation_id' => $variationQuantity->variation_id,
                'product_name' => $variationQuantity->variation->product->name,
                'variation_name' => $variationQuantity->variation->variation_name,
                'batch_id' => $variationQuantity->batch_id ?? null,
                'batch_name' => $variationQuantity->batch->code ?? null,
                'quantity' => $variationQuantity->quantity,
                'created_at' => $variationQuantity->created_at,
                'updated_at' => $variationQuantity->updated_at,
            ];
            return responseApi($data, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }
}
