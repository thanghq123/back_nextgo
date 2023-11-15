<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'amount_in' => 'required|numeric',
            'amount_refund' => 'required|numeric',
            'payment_method' => 'required|in:0,1,2',
//            'created_by' => 'required',
        ];
    }
    public function messages()
    {
        return [
            "required" => "Không được để trống!",
            "in" => "Giá trị không hợp lệ!",
            "numeric" => "Chỉ được nhập số!"
        ];
    }
}
