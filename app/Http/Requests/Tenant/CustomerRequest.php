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
        $getUrl = Str::afterLast($this->url(), '/');

        $rules = [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Customer,id"
            ],
            "group_customer_id" => "exists:App\Models\Tenant\GroupCustomer,id",
            "type" => "in:0,1",
            "name" => [
                "required",
                "max:255",
                "unique" => "unique:App\Models\Tenant\Customer,name"
            ],
            "gender" => "in:0,1,2",
            "dob" => "date",
            "email" => [
                "regex" => "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                "max" => "max:255",
                "unique" => "unique:App\Models\Tenant\Customer,email"
            ],
            "tel" => [
                "required",
                "max:255",
                "unique" => "unique:App\Models\Tenant\Customer,tel",
                "regex:/^(03|05|07|08|09)[0-9]{7,10}$/"
            ],
            "status" => "in:0,1",
            "province_code" => [
                "nullable",
                "numeric"
            ],
            "district_code" => [
                "nullable",
                "numeric"
            ],
            "ward_code" => [
                "numeric",
                "nullable"
            ],
            "address_detail" => "max:500",
            "note" => "max:500"
        ];

        switch ($getUrl){
            case "store":
                return [
                    "group_customer_id" => $rules["group_customer_id"],
                    "type" => $rules["type"],
                    "name" => $rules["name"],
                    "gender" => $rules["gender"],
                    "dob" => $rules["dob"],
                    "email" => $rules["email"],
                    "tel" => $rules["tel"],
                    "status" => $rules["status"],
                    "province_code" => $rules["province_code"],
                    "district_code" => $rules["district_code"],
                    "ward_code" => $rules["ward_code"],
                    "address_detail" => $rules["address_detail"],
                    "note" => $rules['note']
                ];
            case "update":
                return [
                    "id" => $rules["id"],
                    "group_customer_id" => $rules["group_customer_id"],
                    "type" => $rules["type"],
                    "name" => [
                        $rules["name"],
                        $rules["name"]["unique"].",".$this->id
                    ],
                    "gender" => $rules["gender"],
                    "dob" => $rules["dob"],
                    "email" => [
                        $rules["email"],
                        $rules["email"]["unique"].",".$this->id
                    ],
                    "tel" => [
                        $rules["tel"],
                        $rules["tel"]["unique"].",".$this->id
                    ],
                    "status" => $rules["status"],
                    "province_code" => $rules["province_code"],
                    "district_code" => $rules["district_code"],
                    "ward_code" => $rules["ward_code"],
                    "address_detail" => $rules["address_detail"],
                    "note" => $rules['note']
                ];
            case "show":
            case "delete":
                return ["id" => $rules["id"]];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            "required" => "Không được để trống!",
            "exists" => "Dữ liệu không tồn tại!",
            "in" => "Giá trị không hợp lệ!",
            "unique" => "Dữ liệu đã tồn tại!",
            "max" => "Bạn đã vượt quá ký tự cho phép!",
            "date" => "Sai định dạng ngày!",
            "regex" => "Sai định dạng!",
            "numeric" => "Chỉ được nhập số!"
        ];
    }
}
