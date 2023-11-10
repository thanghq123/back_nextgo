<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SupplierRequest;
use App\Models\Tenant\Customer;
use App\Models\Tenant\Supplier;

class SupplierController extends Controller
{
    public function __construct(
        private Customer $model,
        private SupplierRequest $request,
    )
    {
    }

    public function list(){
        try {
            return responseApi($this->model::query()
                ->where('customer_type', 1)
                ->select('customers.*')
                ->selectRaw('(SELECT name FROM group_customers
                                                   WHERE id = customers.group_customer_id and type = 1)
                                                   as group_supplier_name')
                ->orderBy('id', "desc")
                ->paginate(10), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store(){
        try {
            $this->model::create([
                ...$this->request->all(),
                'customer_type' => 1
            ]);
            return responseApi("Tạo thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            return responseApi($this->model::query()
                ->where('customer_type', 1)
                ->select('customers.*')
                ->selectRaw('(SELECT name FROM group_customers
                                                   WHERE id = customers.group_customer_id and type = 1)
                                                   as group_supplier_name')
                ->where('id', $this->request->id)
                ->first(), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            $this->model::find($this->request->id)->update($this->request->all());
            return responseApi("Cập nhật thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete(){
        try {
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
