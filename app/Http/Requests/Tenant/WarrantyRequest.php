<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class WarrantyRequest extends FormRequest
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
                "exists:App\Models\Tenant\Warranty,id"
            ],
            "name" => [
                "required",
                "max:255",
                "unique" => "unique:App\Models\Tenant\Warranty,name"
            ],
            "unit" => [
                "required",
                "in:0,1,2"
            ],
            "period" => [
                "required",
                "gt:0",
                "max:500"
            ]
        ];

        switch ($getUrl){
            case "store":
                return [
                    "name" => $rules["name"],
                    "unit" => $rules["unit"],
                    "period" => $rules["period"]
                ];
            case "update":
                return [
                    "id" => $rules["id"],
                    "name" => [
                        $rules["name"],
                        $rules["name"]["unique"].",".$this->id
                    ],
                    "unit" => $rules["unit"],
                    "period" => $rules["period"]
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
            "unique" => "Dữ liệu đã tồn tại!",
            "gt" => "Dữ liệu không hợp lệ!",
            "max" => "Bạn đã vượt quá ký tự cho phép :max!",
            "in" => "Dữ liệu không hợp lệ!"
        ];
    }
}
