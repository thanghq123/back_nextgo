<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'tenant_id' => 'required|exists:tenants,id',
            'pricing_id' => 'required|exists:pricings,id',
            'type' => 'required|in:0,1',
            'name' => 'required|min:3',
            'tel' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ];
    }
    public function messages()
    {
        return [
            'tenant_id.required' => 'Không được để trống!',
            'tenant_id.exists' => 'Không tồn tại!',
            'pricing_id.required' => 'Không được để trống!',
            'pricing_id.exists' => 'Không tồn tại!',
            'type.required' => 'Không được để trống!',
            'type.in' => 'Không tồn tại!',
            'name.required' => 'Không được để trống!',
            'name.min' => 'Ít nhất 3 ký tự!',
            'tel.required' => 'Không được để trống!',
            'tel.regex' => 'Số điện thoại không hợp lệ!',
            'tel.min' => 'Ít nhất 10 ký tự!',
        ];
    }
}
