<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CustomerRequest extends FormRequest
{
    use TFailedValidation;

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
        $url = Str::afterLast($this->url(), '/');

        if ($url == "store") {
            return [
                "group_customer_id" => "required",
                "type" => [
                    "required",
                    "in:0,1"
                ],
                "name" => [
                    "required",
                    "max:255",
                    "unique:App\Models\Tenant\Customer,name"
                ],
                "gender" => [
                    "required",
                    "in:0,1,2"
                ],
                "dob" => [
                    "required",
                    "date"
                ],
                "email" => [
                    "required",
                    "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                    "max:255",
                    "unique:App\Models\Tenant\Customer,email"
                ],
                "tel" => [
                    "required",
                    "max:255",
                    "unique:App\Models\Tenant\Customer,tel",
                    "regex:/^(03|05|07|08|09)[0-9]{7,10}$/"
                ],
                "status" => [
                    "required",
                    "in:0,1"
                ],
                "province_code" => [
                    "required",
                    "numeric"
                ],
                "district_code" => [
                    "required",
                    "numeric"
                ],
                "ward_code" => [
                    "required",
                    "numeric"
                ],
                "address_detail" => [
                    "required",
                    "max:1000"
                ]
            ];
        }

        if ($url == "update") {
            return [
                "group_customer_id" => "required",
                "type" => [
                    "required",
                    "in:0,1"
                ],
                "name" => [
                    "required",
                    "max:255",
                    "unique:App\Models\Tenant\Customer,name,".$this->id
                ],
                "gender" => [
                    "required",
                    "in:0,1,2"
                ],
                "dob" => [
                    "required",
                    "date"
                ],
                "email" => [
                    "required",
                    "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                    "max:255",
                    "unique:App\Models\Tenant\Customer,email,".$this->id
                ],
                "tel" => [
                    "required",
                    "max:255",
                    "unique:App\Models\Tenant\Customer,tel,".$this->id,
                    "regex:/^(03|05|07|08|09)[0-9]{7,10}$/"
                ],
                "status" => [
                    "required",
                    "in:0,1"
                ],
                "province_code" => [
                    "required",
                    "numeric"
                ],
                "district_code" => [
                    "required",
                    "numeric"
                ],
                "ward_code" => 'numeric',
                "address_detail" => [
                    "required",
                    "max:1000"
                ]
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            "group_customer_id.required" => "Không được để trống!",
            "type.required" => "Không được để trống!",
            "name.required" => "Không được để trống!",
            "name.unique" => "Tên đã tồn tại!",
            "gender.required" => "Không được để trống!",
            "dob.required" => "Không được để trống!",
            "email.required" => "Không được để trống!",
            "email.regex" => "Email sai định dạng!",
            "email.unique" => "Email đã tồn tại!",
            "email.max" => "Bạn đã vượt quá ký tự cho phép!",
            "tel.required" => "Không được để trống!",
            "tel.unique" => "Số điện thoại đã tồn tại!",
            "tel.regex" => "Số điện thoại không hợp lệ!",
            "tel.max" => "Bạn đã vượt quá ký tự cho phép!",
            "status.required" => "Không được để trống!",
            "status.in" => "Giá trị không hợp lệ!",
            "province_code.required" => "Không được để trống!",
            "province_code.numeric" => "Chỉ được nhập số!",
            "district_code.required" => "Không được để trống!",
            "district_code.numeric" => "Chỉ được nhập số!",
            "ward_code.numeric" => "Chỉ được nhập số!",
            "address_detail.required" => "Không được để trống!",
            "address_detail.max" => "Bạn đã vượt quá ký tự cho phép!",
        ];
    }
}
