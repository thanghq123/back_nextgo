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

    public function list()
    {
        try {
            $customerData = $this->model::with(['group_customer' => function ($query) {
                $query->where('type', 0);
            }])
                ->where('type', '<>',1)
                ->orderBy('id', 'desc')
                ->get();
            $data = $customerData->map(function ($customerData){
                return [
                    'id' => $customerData->id,
                    'group_customer_id' => $customerData->group_customer_id,
                    'group_customer_name' => $customerData->group_customer->name??null,
                    'type' => $customerData->type,
                    'name' => $customerData->name,
                    'gender' => $customerData->gender,
                    'dob' => $customerData->dob,
                    'email' => $customerData->email,
                    'tel' => $customerData->tel,
                    'status' => $customerData->status,
                    'province_code' => $customerData->province_code,
                    'district_code' => $customerData->district_code,
                    'ward_code' => $customerData->ward_code,
                    'address_detail' => $customerData->address_detail,
                    'note' => $customerData->note,
                    'created_at' => $customerData->created_at,
                    'updated_at' => $customerData->updated_at,
                    'customer_type' => $customerData->customer_type
                ];
            });

            return responseApi($data, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function getListCustomer()
    {
        try {
            if ($this->request->type) {
                $query = Customer::with(['province', 'district', 'commune'])->whereType($this->request->type)->paginate(10);
            } else {
                $query = Customer::with(['province', 'district', 'commune'])->paginate(10);
            }
            $return = $query->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'tel' => $data->tel,
                    'name_tel' => $data->name . ' - ' .$data->tel,
                    'email' => $data->email,
                    'status' => $data->status,
                    'customer_type' => $data->customer_type,
                    'address' => $data->address_detail,
                    'created_at' => $data->created_at->format('H:i d-m-Y'),
                    'updated_at' => $data->created_at->format('H:i d-m-Y'),
                ];
            });
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function getCustomerWithStatus()
    {
        try {
            $query = Customer::with(['province', 'district', 'commune'])->whereStatus(1)->paginate(10);
            $return = $query->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'tel' => $data->tel,
                    'email' => $data->email,
                    'status' => $data->status,
                    'address' => $data->address_detail,
                    'created_at' => $data->created_at->format('H:i d-m-Y'),
                    'updated_at' => $data->created_at->format('H:i d-m-Y'),
                ];
            });
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function searchCustomer()
    {
        try {
            $name = $this->request->name;
            $tel = $this->request->tel;
            $customers = Customer::query()
                ->where('name', 'like', '%' . $name . '%')
                ->orWhere('tel', 'like', '%' . $tel . '%')
                ->get();
            return responseApi($customers, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function store()
    {
        try {
            $this->model::create($this->request->all());
            if ($this->request->statusCreate == 1) {
                return $this->list();
            }else{
                return responseApi("Tạo thành công!", true);
            }
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            $customerData = $this->model::with(['group_customer'])->where('id', $this->request->id)->get();

            $data = $customerData->map(function ($customerData) {
                return [
                    'id' => $customerData->id,
                    'group_customer_id' => $customerData->group_customer_id,
                    'group_customer_name' => $customerData->group_customer?->name,
                    'type' => $customerData->type,
                    'name' => $customerData->name,
                    'gender' => $customerData->gender,
                    'dob' => $customerData->dob,
                    'email' => $customerData->email,
                    'tel' => $customerData->tel,
                    'status' => $customerData->status,
                    'province_code' => $customerData->province_code,
                    'district_code' => $customerData->district_code,
                    'ward_code' => $customerData->ward_code,
                    'address_detail' => $customerData->address_detail,
                    'note' => $customerData->note,
                    'created_at' => $customerData->created_at,
                    'updated_at' => $customerData->updated_at,
                    'customer_type' => $customerData->customer_type
                ];
            });

            return responseApi(collect($data)->collapse(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            $this->model::find($this->request->id)->update($this->request->all());
            return responseApi("Cập nhật thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete()
    {
        try {
            $this->model::find($this->request->id)->delete();
            return responseApi("Xóa thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
