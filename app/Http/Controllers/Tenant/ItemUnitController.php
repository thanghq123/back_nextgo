<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ItemUnitRequest;
use App\Models\Tenant\ItemUnit;
use Illuminate\Http\Request;

class ItemUnitController extends Controller
{
    //
    public function __construct(
        private ItemUnit $model,
        private ItemUnitRequest $request,
        private string $module_name = "Đơn vị tính",
    )
    {
    }

    public function list(){
        try {
            return responseApi($this->model::all(), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store(){
        try {
            if (!empty($this->request->validated())) {
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
            return responseApi($this->model::find($this->request->id), true);
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
