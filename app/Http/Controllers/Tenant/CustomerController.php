<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CustomerRequest;
use App\Models\Tenant\Customer;

class CustomerController extends Controller
{
    public function __construct(
        private Customer $model,
        private CustomerRequest $request
    )
    {
    }

    public function list(){
        try {
            $data = $this->model::with(['group_customer'])
                ->orderBy('id', "desc")
                ->get();

            $dataMap = $data->map(function ($data){
                return [
                    'id' => $data->id,
                    'type' => $data->type,
                    'gender' => $data->gender,
                    'dob' => $data->dob,
                    'email' => $data->email,
                    'tel' => $data->tel,
                    'status' => $data->status,
                    'province_code' => $data->province_code,
                    'district_code' => $data->district_code,
                    'ward_code' => $data->ward_code,
                    'address_detail' => $data->address_detail,
                    'note' => $data->note,
                    'group_customer_name' => $data->group_customer->name ?? null
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
            $data = $this->model::with(['group_customer'])
                ->orderBy('id', "desc")
                ->where('id', $this->request->id)
                ->get();

            $dataMap = $data->map(function ($data){
                return [
                    'id' => $data->id,
                    'type' => $data->type,
                    'gender' => $data->gender,
                    'dob' => $data->dob,
                    'email' => $data->email,
                    'tel' => $data->tel,
                    'status' => $data->status,
                    'province_code' => $data->province_code,
                    'district_code' => $data->district_code,
                    'ward_code' => $data->ward_code,
                    'address_detail' => $data->address_detail,
                    'note' => $data->note,
                    'group_customer_name' => $data->group_customer->name ?? null
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
