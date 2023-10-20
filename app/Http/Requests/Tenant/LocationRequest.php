<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'tel' => 'required|regex:/0[3|5|7|8|9])+([0-9]{8})\b/g',
            'address_detail' => 'required',
            'status' => 'required|integer|min:0',
            'is_main' => 'required|integer|min:0'
        ];
    }

    public function messages()
    {
        return [
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
    }
}
