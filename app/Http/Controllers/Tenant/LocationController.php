<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Address\Commune;
use App\Models\Tenant\Inventory;
use App\Models\Tenant\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
                "regex:/^0[0-9]{9}$/"
            ],
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|integer|min:0',
            'is_main' => 'required|integer|min:0'
        ];
        $message = [
            'name.required' => 'Tên phải được nhập',
            'tel.required' => 'Số điện thoại phải được nhập',
            'tel.regex' => 'Số điện thoại không hợp lệ',
            'image.required' => 'Ảnh phải được nhập',
            'image.image' => 'Ảnh không đúng định dạng',
            'image.mimes' => 'Ảnh không hợp lệ',
            'image.max' => 'Ảnh không đc quá :max mb',
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
            $locations = Location::with(['inventories'])->get();
            $return = $locations->map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'image' => $data->image,
                    'description' => $data->description,
                    'tel' => $data->tel,
                    'email' => $data->email,
                    'address_detail' => $data->address_detail,
                    'status' => $data->status,
                    'is_main' => $data->is_main,
                    'created_by' => $data->created_by,
                    'inventory' => $data->inventories->first(),
                ];
            });
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            $data = Location::query()->findOrFail($request->id);
            $return = [
                'name' => $data->name,
                'image' => $data->image,
                'description' => $data->description,
                'tel' => $data->tel,
                'email' => $data->email,
                'address_detail' => $data->address_detail,
                'status' => $data->status,
                'is_main' => $data->is_main,
                'created_by' => $data->created_by
            ];
            return responseApi($return, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validation($request);
            $file = $request->file('image');
            if ($file) {
                $fileName = $file->getClientOriginalName();
                $fullpath = $file->storeAs('images', $fileName, 'public');
            }
            $address = Commune::with(['district', 'district.province'])->whereId($request->ward_code)->first();
            $data = [
                'name' => $request->name,
                'image' => $fullpath ?? null,
                'description' => $request->description ?? null,
                'tel' => $request->tel ?? null,
                'email' => $request->email ?? null,
                'province_code' => $request->province_code ?? null,
                'district_code' => $request->district_code ?? null,
                'ward_code' => $request->ward_code ?? null,
                'address_detail' => $request->address_detail,
                'status' => $request->status ?? 1,
                'is_main' => $request->is_main ?? 0,
                'created_by' => $request->created_by ?? null
            ];
            $this->createLocationAndInventory($data);
            DB::commit();
            return responseApi('Thêm thành công', true);
        } catch (ValidationException $exception) {
            DB::rollBack();
            return responseApi($exception->errors());
        }
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validation($request);
            $location = Location::query()->find($request->id);
            if ($request->hasFile('image')) {
                Storage::delete('public/' . $location->image);
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName();
                $fullpath = $file->storeAs('images', $fileName, 'public');
            } else {
                $fullpath = $location->image;
            }
            $address = Commune::with(['district', 'district.province'])->whereId($request->ward_code)->first();
            $data = [
                'name' => $request->name,
                'image' => $fullpath,
                'description' => $request->description,
                'tel' => $request->tel,
                'email' => $request->email,
                'province_code' => $request->province_code,
                'district_code' => $request->district_code,
                'ward_code' => $request->ward_code,
                'address_detail' => $request->address_detail,
                'status' => $request->status,
                'is_main' => $request->is_main,
                'created_by' => $request->created_by
            ];
            $location->update($data);
            Inventory::query()->where('location_id', $request->id)->update([
                'location_id' => $request->id,
                'name' => 'Kho ' . $request->name,
                'status' => $request->status,
                'code' => Str::slug('Kho ' . $request->name)
            ]);
            DB::commit();
            return responseApi('Cập nhật thành công', true);
        } catch (ValidationException $exception) {
            DB::rollBack();
            return responseApi($exception->errors());
        }
    }

    public function delete(Request $request)
    {
        try {
            $location = Location::query()->findOrFail($request->id);
            $location?->delete();
            Storage::delete('public/' . $location->image);
            Inventory::query()->where('location_id', $request->id)->delete();
            return responseApi('Xoá thành công', true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function createLocationAndInventory($data)
    {
        $location = Location::query()->create($data);
        Inventory::query()->create([
            'location_id' => $location->id,
            'name' => 'Kho ' . $location->name,
            'status' => $location->status,
            'code' => Str::slug('Kho ' . $location->name)
        ]);
    }
}
