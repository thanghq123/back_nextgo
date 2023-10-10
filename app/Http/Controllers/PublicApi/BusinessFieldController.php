<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Models\BusinessField;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BusinessFieldController extends Controller
{
    public function list()
    {
        return responseApi(BusinessField::all(), true);
    }

    public function getById(Request $request)
    {
        $bf = BusinessField::find($request->id);
        if (!$bf) return responseApi('Có lỗi xảy ra');
        return responseApi($bf, true);
    }

    public function validation(Request $request)
    {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'detail' => 'required'
        ];
        $message = [
            'name.required' => 'Tên phải được nhập',
            'code.required' => 'Code phải được nhập',
            'detail.required' => 'Mô tả phải được nhập'
        ];
        return $request->validate($rules, $message);
    }

    public function create(Request $request)
    {
        try {
            $this->validation($request);
            $data = [
                'name' => $request->name,
                'code' => $request->code,
                'detail' => $request->detail
            ];
            $bf = new BusinessField();
            $bf->name = $data['name'];
            $bf->code = $data['code'];
            $bf->detail = $data['detail'];
            $bf->save();
            return responseApi('Tạo thành công', true);
        } catch (ValidationException $exception) {
            return responseApi($exception->errors());
        }
    }

    public function update(Request $request)
    {
        $bf = BusinessField::find($request->id);
        if (!$bf) return responseApi('Có lỗi xảy ra');
        try {
            $this->validation($request);
            $data = [
                'name' => $request->name,
                'code' => $request->code,
                'detail' => $request->detail
            ];
            $bf->name = $data['name'];
            $bf->code = $data['code'];
            $bf->detail = $data['detail'];
            $bf->save();
            return responseApi('Cập nhật thành công', true);
        } catch (ValidationException $exception) {
            return responseApi($exception->errors());
        }
    }

    public function delete(Request $request)
    {
        $bf = BusinessField::find($request->id);
        if (!$bf) {
            return responseApi('Có lỗi xảy ra');
        } else {
            $bf->delete();
            return responseApi('Xóa thành công', true);
        }
    }
}
