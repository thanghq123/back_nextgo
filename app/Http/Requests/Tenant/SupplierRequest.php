<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class SupplierRequest extends FormRequest
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

        switch ($getUrl){
            case "store":
                return [
                    "group_supplier_id" => "exists:App\Models\Tenant\GroupSupplier,id",
                    "type" => "in:0,1",
                    "name" => [
                        "required",
                        "max:255",
                        "unique:App\Models\Tenant\Supplier,name"
                    ],
                    "email" => [
                        "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                        "max:255",
                        "unique:App\Models\Tenant\Supplier,email"
                    ],
                    "tel" => [
                        "required",
                        "max:255",
                        "unique:App\Models\Tenant\Supplier,tel",
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
            case "update":
                return [
                    "id" => [
                        "required",
                        "exists:App\Models\Tenant\Supplier,id"
                    ],
                    "group_supplier_id" => "exists:App\Models\Tenant\GroupSupplier,id",
                    "type" => "in:0,1",
                    "name" => [
                        "required",
                        "max:255",
                        "unique:App\Models\Tenant\Supplier,name,".$this->id
                    ],
                    "email" => [
                        "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                        "max:255",
                        "unique:App\Models\Tenant\Supplier,email,".$this->id
                    ],
                    "tel" => [
                        "required",
                        "max:255",
                        "unique:App\Models\Tenant\Supplier,tel,".$this->id,
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
            case "show":
            case "delete":
                return [
                    "id" => [
                        "required",
                        "exists:App\Models\Tenant\Supplier,id"
                    ]
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            "required" => "Không được để trống!",
            "exists" => "Dữ liệu không tồn tại!",
            "in" => "Dữ liệu không hợp lệ!",
            "unique" => "Dữ liệu đã tồn tại!",
            "max" => "Bạn đã vượt quá ký tự cho phép!",
            "regex" => "Sai định dạng!",
            "numeric" => "Chỉ được nhập số!"
        ];
    }
}
