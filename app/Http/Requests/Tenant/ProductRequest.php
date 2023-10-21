<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ProductRequest extends FormRequest
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

        $rules =  [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Product,id"
            ],
            "name" => [
                "required",
                "max:255"
            ],
            "image" => [
                "nullable",
                "max:255"
            ],
            "weight" => [
                "nullable",
                "max:10000"
            ],
            "description" => [
                "nullable",
                "max: 500"
            ],
            "manage_type" => [
                "required",
                "in:0,1"
            ],
            "brand_id" => [
                "exists:App\Models\Tenant\Brand,id",
                "nullable"
            ],
            "warranty_id" => [
                "exists:App\Models\Tenant\Warranty,id",
                "nullable"
            ],
            "item_unit_id" => [
                "exists:App\Models\Tenant\ItemUnit,id",
                "nullable"
            ],
            "category_id" => [
                "exists:App\Models\Tenant\Category,id",
                "nullable"
            ],
            "status" => [
                "required",
                "in:0,1"
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];
                return [
                    "id" => $updateId,
                    "name" => $rules["name"],
                    "image" => $rules["image"],
                    "weight" => $rules["weight"],
                    "description" => $rules["description"],
                    "manage_type" => $rules["manage_type"],
                    "brand_id" => $rules["brand_id"],
                    "warranty_id" => $rules["warranty_id"],
                    "item_unit_id" => $rules["item_unit_id"],
                    "category_id" => $rules["category_id"],
                    "status" => $rules["status"]
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
            "max" => "Bạn đã vượt quá ký tự cho phép!"
        ];
    }
}
