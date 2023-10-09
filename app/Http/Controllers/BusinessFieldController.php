<?php

namespace App\Http\Controllers;

use App\Interface\BusinessFieldInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BusinessFieldController extends Controller
{
    private $business;

    public function __construct(BusinessFieldInterface $business)
    {
        $this->business = $business;
    }

    public function list()
    {
        return $this->business->list();
    }

    public function getById(Request $request)
    {
        return $this->business->getById($request->id);
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
        if ($request->isMethod('post')) {
            try {
                $this->validation($request);
                $data = [
                    'name' => $request->name,
                    'code' => $request->code,
                    'detail' => $request->detail
                ];
                return $this->business->create($data);
            } catch (ValidationException $exception) {
                return responseApi($exception->errors());
            }
        }
    }

    public function update(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $this->validation($request);
                $data = [
                    'name' => $request->name,
                    'code' => $request->code,
                    'detail' => $request->detail
                ];
                return $this->business->update($request->id, $data);
            } catch (ValidationException $exception) {
                return responseApi($exception->errors());
            }
        }
    }

    public function delete(Request $request)
    {
        return $this->business->delete($request->id);
    }
}
