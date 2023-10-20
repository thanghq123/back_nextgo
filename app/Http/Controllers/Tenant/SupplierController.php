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
            $data = $this->model::with(['group_supplier'])
                ->orderBy('id', "desc")
                ->get();

            $dataMap = $data->map(function ($data){
                return [
                    'id' => $data->id,
                    'type' => $data->type,
                    'name' => $data->name,
                    'email' => $data->email,
                    'tel' => $data->tel,
                    'status' => $data->status,
                    'province_code' => $data->province_code,
                    'district_code' => $data->district_code,
                    'ward_code' => $data->ward_code,
                    'address_detail' => $data->address_detail,
                    'note' => $data->note,
                    'group_supplier_name' => $data->group_supplier->name ?? null
                ];
            });

            return responseApi($dataMap, true);
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
            $data = $this->model::with(['group_supplier'])
                ->orderBy('id', "desc")
                ->where('id', $this->request->id)
                ->get();

            $dataMap = $data->map(function ($data){
                return [
                    'id' => $data->id,
                    'type' => $data->type,
                    'name' => $data->name,
                    'email' => $data->email,
                    'tel' => $data->tel,
                    'status' => $data->status,
                    'province_code' => $data->province_code,
                    'district_code' => $data->district_code,
                    'ward_code' => $data->ward_code,
                    'address_detail' => $data->address_detail,
                    'note' => $data->note,
                    'group_supplier_name' => $data->group_supplier->name ?? null
                ];
            });

            return responseApi($dataMap, true);
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
