<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\InventoryTransaction;
use App\Models\Tenant\InventoryTransactionDetail;
use App\Http\Requests\Tenant\InventoryTransactionRequest;
use Illuminate\Support\Facades\DB;

class InventoryTransactionController extends Controller
{
    public function __construct(
        private InventoryTransaction        $model,
        private InventoryTransactionRequest $request
    )
    {
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $inventoryTransaction = $this->model::create($this->request->all());
            $inventoryTransaction->inventoryTransactionDetails()->createMany($this->request->inventory_transaction_details);
            DB::commit();
            return responseApi("Tạo thành công!", true);
        }catch (\Throwable $throwable){
            DB::rollBack();
            return responseApi($throwable->getMessage(), false);
        }
    }
}
