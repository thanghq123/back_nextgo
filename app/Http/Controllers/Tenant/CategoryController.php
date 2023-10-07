<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CategoryRequest;
use App\Models\Tenant\Category;

class CategoryController extends Controller
{
    public function __construct(
        private Category $model
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

    public function store(CategoryRequest $request){
        try {
            if (!empty($request->validated())) {
                $this->model::create($request->all());
                return responseApi('Tạo thành công!', true);
            }
            return responseApi('Tạo thất bại!', false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show(CategoryRequest $request)
    {
        try {
            if (!$this->model::find($request->id)) return responseApi('Loại không tồn tại!', false);
            return responseApi($this->model::find($request->id), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update(CategoryRequest $request)
    {
        try {
            if (!$this->model::find($request->id)) return responseApi('Loại không tồn tại!', false);
            if (!empty($request->validated())) {
                $category = $this->model::find($request->id);
                $category->update($request->all());
                return responseApi('Cập nhật thành công!', true);
            }
            return responseApi('Cập nhật thất bại', false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete(CategoryRequest $request){
        try {
            if (!$this->model::find($request->id)) return responseApi('Loại không tồn tại!', false);

            $this->model::find($request->id)->delete();

            return responseApi('Xóa thành công!', true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}

