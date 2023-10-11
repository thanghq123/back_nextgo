<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CustomerRequest;
use App\Models\Tenant\Customer;
use App\Models\Tenant\GroupCustomer;

class CustomerController extends Controller
{
    public function __construct(
        private Customer $model,
        private GroupCustomer $groupCustomer,
        private CustomerRequest $request,
        private string $module_name = "Khách hàng",
    )
    {
    }

    public function list(){
        try {
            return responseApi($this->model::query()
                ->select('customers.*')
                ->selectRaw('(SELECT name FROM group_customers
                                                   WHERE id = customers.group_customer_id)
                                                   as group_customer_name')
                ->paginate(10), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store(){
        try {
            if (!empty($this->request->validated())) {
                if (!$this->groupCustomer::find($this->request->group_customer_id))
                    return responseApi("Nhóm khách hàng không tồn tại!", false);
                $this->model::create($this->request->all());
                return responseApi("Tạo thành công!", true);
            }
            return responseApi("Tạo thất bại!", false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show()
    {
        try {
            if (!$this->model::find($this->request->id))
                return responseApi($this->module_name." không tồn tại!", false);
            return responseApi($this->model::query()
                ->select('customers.*')
                ->selectRaw('(SELECT name FROM group_customers
                                                   WHERE id = customers.group_customer_id)
                                                   as group_customer_name')
                ->first(), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update()
    {
        try {
            if (!$this->model::find($this->request->id))
                return responseApi($this->module_name." không tồn tại!", false);

            if (!$this->groupCustomer::find($this->request->group_customer_id))
                return responseApi("Nhóm khách hàng không tồn tại!", false);

            if (!empty($this->request->validated())) {
                $category = $this->model::find($this->request->id);
                $category->update($this->request->all());
                return responseApi("Cập nhật thành công!", true);
            }
            return responseApi("Cập nhật thất bại!", false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete(){
        try {
            if (!$this->model::find($this->request->id))
                return responseApi($this->module_name." không tồn tại!", false);

            $this->model::find($this->request->id)->delete();

            return responseApi("Xóa thành công!", true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}
