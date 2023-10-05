<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriesRequest;
use App\Models\Tenant\Category;

class CategoriesController extends Controller
{
    public function __construct(
        public Category $category
    )
    {
    }

    public function list(){
        try {
            return responseApi($this->category::all(), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function store(CategoriesRequest $request){
        try {
            if (!empty($request->validated())) {
                $this->category::create($request->all());
                return responseApi('Create successfully!', true);
            }
            return responseApi('false', false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function show($id)
    {
        try {
            if (!$this->category::find($id)) return responseApi('Category not found', false);
            return responseApi($this->category::find($id), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update(CategoriesRequest $request, $id)
    {
        try {
            if (!$this->category::find($id)) return responseApi('Category not found', false);
            if (!empty($request->validated())) {
                $category = $this->category::find($id);
                $category->update($request->all());
                return responseApi('Update successfully!', true);
            }
            return responseApi('false', false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function destroy($id){
        try {
            if (!$this->category::find($id)) return responseApi('Category not found', false);

            $this->category::find($id)->delete();

            return responseApi('Delete successfully!', true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}

