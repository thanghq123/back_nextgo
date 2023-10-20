<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ProductRequest;
use App\Models\Tenant\Product;

class ProductController extends Controller
{
    public function __construct(
        private Product $model,
        private ProductRequest $request
    )
    {
    }

    public function list(){
        try {
            return responseApi($this->model::query()
                ->select('products.*')
                ->selectRaw('(SELECT name FROM brands
                                                   WHERE id = products.brand_id)
                                                   as brand_name')
                ->selectRaw('(SELECT name FROM warranties
                                                   WHERE id = products.warranty_id)
                                                   as warranty_name')
                ->selectRaw('(SELECT name FROM item_units
                                                   WHERE id = products.item_unit_id)
                                                   as item_unit_name')
                ->selectRaw('(SELECT name FROM categories
                                                   WHERE id = products.category_id)
                                                   as category_name')
                ->selectRaw('DATE_FORMAT(created_at, "%d/%m/%Y %H:%i") as format_created_at')
                ->selectRaw('DATE_FORMAT(updated_at, "%d/%m/%Y %H:%i") as format_updated_at')
                ->orderBy('id', 'desc')
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
                ->select('products.*')
                ->selectRaw('(SELECT name FROM brands
                                                   WHERE id = products.brand_id)
                                                   as brand_name')
                ->selectRaw('(SELECT name FROM warranties
                                                   WHERE id = products.warranty_id)
                                                   as warranty_name')
                ->selectRaw('(SELECT name FROM item_units
                                                   WHERE id = products.item_unit_id)
                                                   as item_unit_name')
                ->selectRaw('(SELECT name FROM categories
                                                   WHERE id = products.category_id)
                                                   as category_name')
                ->selectRaw('DATE_FORMAT(created_at, "%d/%m/%Y %H:%i") as format_created_at')
                ->selectRaw('DATE_FORMAT(updated_at, "%d/%m/%Y %H:%i") as format_updated_at')
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
