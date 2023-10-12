<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\SupplierRequest;
use App\Models\Tenant\Supplier;

class SupplierController extends Controller
{
    public function __construct(
        private Supplier $model,
        private SupplierRequest $request,
    )
    {
    }

    public function list(){
        try {
            return responseApi($this->model::query()
                ->select('suppliers.*')
                ->selectRaw('(SELECT name FROM group_suppliers
                                                   WHERE group_suppliers.id = suppliers.group_supplier_id)
                                                   as group_supplier_name')
                ->paginate(10), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store(){
        try {
            $this->model::create($this->request->all());
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
                ->select('suppliers.*')
                ->selectRaw('(SELECT name FROM group_suppliers
                                                   WHERE group_suppliers.id = suppliers.group_supplier_id)
                                                   as group_supplier_name')
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
