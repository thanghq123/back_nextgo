<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Tenant\InventoryTransaction;
use App\Models\Tenant\VariationQuantity;
use App\Http\Requests\Tenant\InventoryTransactionRequest;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    public function __construct(
        private InventoryTransaction $model,
        private VariationQuantity    $variationQuantityModel,
    )
    {
    }

    /**
     * @path /tenant/api/v1/storage/import/create
     * @method POST
     * @param InventoryTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function store(InventoryTransactionRequest $request)
    {
        $inventory_transaction_id = Carbon::now()->timestamp;
        DB::beginTransaction();
        try {
            $inventoryTransaction = $this->model::create([
                "inventory_id" => $request->inventory_id,
                "partner_id" => $request->partner_id,
                "partner_type" => $request->partner_type,
                "trans_type" => $request->trans_type,
                "inventory_transaction_id" => $inventory_transaction_id,
                "reason" => $request->reason,
                "note" => $request->note,
                "status" => $request->status,
                "created_by" => $request->created_by
            ]);
            $data = json_decode($request->inventory_transaction_details, true);
            $details = collect($data)->toArray();
            $inventoryTransaction->inventoryTransactionDetails()->createMany($details);
            DB::commit();
            return responseApi("Tạo thành công!", true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }
    /**
     * @path /tenant/api/v1/storage/import
     * @method POST
     * @param InventoryTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function list()
    {
        try {
            $inventoryTransactionData = $this->model::with('inventory','partner','createdBy')->get();
            $data=$inventoryTransactionData->map(function ($inventoryTransactionData){
                return [
                    "inventory_transaction_id"=>$inventoryTransactionData->inventory_transaction_id,
                    "partner_name"=>$inventoryTransactionData->partner->name,
                    "inventory_name"=>$inventoryTransactionData->inventory->name,
                    "created_by"=>$inventoryTransactionData->createdBy->name,
                    "status"=>$inventoryTransactionData->status,
                    "created_at"=>Carbon::make($inventoryTransactionData->created_at)->format('H:i d-m-Y'),
                    "updated_at"=>Carbon::make($inventoryTransactionData->updated_at)->format('H:i d-m-Y'),
                ];
            });
            return responseApi($data, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
    /**
     * @path /tenant/api/v1/storage/import/{id}
     * @method POST
     * @param InventoryTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function show($id)
    {
        try {
            return responseApi($this->model::with('inventoryTransactionDetails')->find($id), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/storage/import/update/{id}
     * @method POST
     * @param InventoryTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function update($id)
    {
        DB::beginTransaction();
        try {
            $inventoryTransaction = $this->model::with('inventoryTransactionDetails')->where('id', $id);
            $inventoryTransaction->update(["status" => 1]);
            $inventoryTransaction = $inventoryTransaction->get();
            foreach ($inventoryTransaction[0]->inventoryTransactionDetails as $item => $value) {
                $variationQuantity = $this->variationQuantityModel::where('variation_id', $value->variation_id);
                $variationQuantityData = $variationQuantity->get();
                if ($variationQuantityData->count() > 0) {
                    $variationQuantity->update([
                        "quantity" => $variationQuantityData[0]->quantity + $value->quantity
                    ]);
                } else {
                    VariationQuantity::create([
                        'variation_id' => $value->variation_id,
                        'inventory_id' => $inventoryTransaction[0]->inventory_id,
                        'batch_id' => $value->batch_id,
                        'price_import' => $value->price,
                        'quantity' => $value->quantity,
                    ]);
                }
            }
            DB::commit();
            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }

    /**
     * @path /tenant/api/v1/storage/import/cancel/{id}
     * @method POST
     * @param InventoryTransactionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function cancel($id)
    {
        try {
            $this->model::find($id)->update(["status" => 2]);
            return responseApi("Huỷ thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}


