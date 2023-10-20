<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\LocationRequest;
use App\Models\Tenant\Inventory;
use App\Models\Tenant\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LocationController extends Controller
{
    public function validation(Request $request)
    {
        $rules = [
            'name' => 'required',
            'tel' => [
                "required",
                "regex:/^(03|05|07|08|09)[0-9]{7,10}$/"
            ],
            'address_detail' => 'required',
            'status' => 'required|integer|min:0',
            'is_main' => 'required|integer|min:0'
        ];
        $message = [
            'name.required' => 'Tên phải được nhập',
            'tel.required' => 'Số điện thoại phải được nhập',
            'tel.regex' => 'Số điện thoại không hợp lệ',
            'address_detail.required' => 'Địa chỉ cụ thể phải được nhập',
            'status.required' => 'Trạng thái phải được chọn',
            'status.integer' => 'Trạng thái không hợp lệ',
            'status.min' => 'Trạng thái không hợp lệ',
            'is_main.required' => 'Cơ sở mặc định phải được chọn',
            'is_main.integer' => 'Cơ sở mặc định không hợp lệ',
            'is_main.min' => 'Cơ sở mặc định không hợp lệ'
        ];
        return $request->validate($rules, $message);
    }

    public function list()
    {
        try {
            return responseApi(Location::all(), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            return responseApi(Location::query()->find($request->id), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validation($request);
            $location = Location::query()->create($request->all());
            Inventory::query()->create([
                'location_id' => $location->id,
                'name' => 'Kho ' . $location->name,
                'status' => $location->status,
                'code' => Str::slug('Kho ' . $location->name)
            ]);
            return responseApi('Thêm thành công', true);
        } catch (ValidationException $exception) {
            return responseApi($exception->errors());
        }

    }

    public function update(Request $request)
    {
        try {
            $this->validation($request);
            Location::query()->find($request->id)->update($request->all());
            Inventory::query()->where('location_id', $request->id)->update([
                'location_id' => $request->id,
                'name' => 'Kho ' . $request->name,
                'status' => $request->status,
                'code' => Str::slug('Kho ' . $request->name)
            ]);
            return responseApi('Cập nhật thành công', true);
        } catch (ValidationException $exception) {
            return responseApi($exception->errors());
        }
    }

    public function delete(Request $request)
    {
        try {
            $location = Location::query()->findOrFail($request->id);
            $location?->delete();
            Inventory::query()->where('location_id',$request->id)->delete();
            return responseApi('Xoá thành công', true);
        } catch (\Throwable $throwable) {
            responseApi($throwable->getMessage());
        }
    }
}
