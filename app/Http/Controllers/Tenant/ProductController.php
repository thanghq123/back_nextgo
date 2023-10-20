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
            $data = $this->model::with(['brands','warranties', 'item_units', 'categories'])
                ->orderBy('id', "desc")
                ->get();

            $dataMap = $data->map(function ($data){
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'image' => $data->image,
                    'weight' => $data->weight,
                    'description' => $data->description,
                    'manage_type' => $data->manage_type,
                    'brand_id' => $data->brands->name ?? null,
                    'warranty_id' => $data->warranties->name ?? null,
                    'item_unit_id' => $data->item_units->name ?? null,
                    'category_id' => $data->categories->name ?? null,
                    'status' => $data->status
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
            $data = $this->model::with(['brands','warranties', 'item_units', 'categories'])
                ->orderBy('id', "desc")
                ->where('id', $this->request->id)
                ->get();

            $dataMap = $data->map(function ($data){
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'image' => $data->image,
                    'weight' => $data->weight,
                    'description' => $data->description,
                    'manage_type' => $data->manage_type,
                    'brand_id' => $data->brands->name ?? null,
                    'warranty_id' => $data->warranties->name ?? null,
                    'item_unit_id' => $data->item_units->name ?? null,
                    'category_id' => $data->categories->name ?? null,
                    'status' => $data->status
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
