<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\GroupSupplierRequest;
use App\Models\Tenant\GroupSupplier;
use App\Models\Tenant\Supplier;

class GroupSupplierController extends Controller
{
    public function __construct(
        private GroupSupplier $model,
        private Supplier $supplierModel,
        private GroupSupplierRequest $request
    )
    {
    }

    public function list(){
        try {
            return responseApi($this->model::query()
                ->orderBy('id','desc')
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
            return responseApi($this->model::find($this->request->id), true);
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
            $this->supplierModel::query()
                ->where('group_supplier_id', $this->request->id)
                ->update(['group_supplier_id' => null]);
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
