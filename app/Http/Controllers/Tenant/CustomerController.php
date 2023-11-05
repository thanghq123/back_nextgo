<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CustomerRequest;
use App\Models\Tenant\Customer;

class CustomerController extends Controller
{
    public function __construct(
        private Customer        $model,
        private CustomerRequest $request
    )
    {
    }

    public function list()
    {
        try {
            return responseApi($this->model::query()
                ->select('customers.*')
                ->selectRaw('(SELECT name FROM group_customers
                                                   WHERE id = customers.group_customer_id)
                                                   as group_customer_name')
                ->orderBy('id', "desc")
                ->paginate(10), true);
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
                    'email' => $data->email,
                    'status' => $data->status,
                    'address' => $data->commune->name . ', ' . $data->district->name . ', ' . $data->province->name,
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
                    'address' => $data->commune->name . ', ' . $data->district->name . ', ' . $data->province->name,
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
            return responseApi("Tạo thành công!", true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            return responseApi($this->model::query()
                ->select('customers.*')
                ->selectRaw('(SELECT name FROM group_customers
                                                   WHERE id = customers.group_customer_id)
                                                   as group_customer_name')
                ->where('id', $this->request->id)
                ->first(), true);
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
