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
        $id = ",".$this->id;
        $rules = [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Customer,id"
            ],
            "group_customer_id" => [
                "exists:App\Models\Tenant\GroupCustomer,id",
                "nullable"
            ],
            "type" => [
                "in:0,1",
                "nullable"
            ],
            "name" => [
                "required",
                "max:255",
                "unique" => "unique:App\Models\Tenant\Customer,name"
            ],
            "gender" => [
                "in:0,1,2",
                "nullable"
            ],
            "dob" => [
                "date",
                "nullable"
            ],
            "email" => [
                "regex" => "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                "max" => "max:255",
                "unique" => "unique:App\Models\Tenant\Customer,email",
                "nullable"
            ],
            "tel" => [
                "required",
                "max:255",
                "unique" => "unique:App\Models\Tenant\Customer,tel",
                "regex:/^(03|05|07|08|09)[0-9]{7,10}$/"
            ],
            "status" => [
                "in:0,1",
                "nullable"
            ],
            "province_code" => [
                "numeric",
                "nullable"
            ],
            "district_code" => [
                "numeric",
                "nullable"
            ],
            "ward_code" => [
                "numeric",
                "nullable"
            ],
            "address_detail" => [
                "max:500",
                "nullable"
            ],
            "note" => [
                "max:500",
                "nullable"
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                $updateName = $getUrl == "update" ? [
                    $rules["name"],
                    $rules["name"]["unique"].$id
                ] :
                    $rules["name"];

                $updateEmail = $getUrl == "update" ? [
                    $rules["email"],
                    $rules["email"]["unique"].$id
                ] :
                    $rules["email"];

                $updateTel = $getUrl == "update" ? [
                    $rules["tel"],
                    $rules["tel"]["unique"].$id
                ] :
                    $rules["tel"];

                return [
                    "id" => $updateId,
                    "group_customer_id" => $rules["group_customer_id"],
                    "type" => $rules["type"],
                    "name" => $updateName,
                    "gender" => $rules["gender"],
                    "dob" => $rules["dob"],
                    "email" => $updateEmail,
                    "tel" => $updateTel,
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
