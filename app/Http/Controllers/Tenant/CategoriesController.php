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

    public function show(CategoriesRequest $request)
    {
        try {
            if (!$this->category::find($request->id)) return responseApi('Category not found', false);
            return responseApi($this->category::find($request->id), true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function update(CategoriesRequest $request)
    {
        try {
            if (!$this->category::find($request->id)) return responseApi('Category not found', false);
            if (!empty($request->validated())) {
                $category = $this->category::find($request->id);
                $category->update($request->all());
                return responseApi('Update successfully!', true);
            }
            return responseApi('false', false);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function destroy(CategoriesRequest $request){
        try {
            if (!$this->category::find($request->id)) return responseApi('Category not found', false);

            $this->category::find($request->id)->delete();

            return responseApi('Delete successfully!', true);
        }catch (\Throwable $throwable)
        {
            return responseApi($throwable->getMessage(), false);
        }
    }
}

