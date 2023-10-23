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
            ],
            "attributes" => [
                "nullable"
            ],
            "attributes.*.id" => [
                "exists:App\Models\Tenant\Attribute,id"
            ],
            "attributes.*.name" => [
                "required",
                "max:255"
            ],
            "attributes.*.attribute_values" => [
                "required"
            ],
            "attributes.*.attribute_values.*.id" => [
                "exists:App\Models\Tenant\AttributeValue,id"
            ],
            "attributes.*.attribute_values.*.value" => [
                "required",
                "max:255"
            ],
            "variations" => [
                "nullable"
            ],
            "variations.*.id" => [
                "exists:App\Models\Tenant\Variation,id"
            ],
            "variations.*.sku" => [
                "nullable",
                "max:255"
            ],
            "variations.*.barcode" => [
                "nullable",
                "max:255"
            ],
            "variations.*.variation_name" => [
                "required",
                "max:255"
            ],
            "variations.*.display_name" => [
                "required",
                "max:255"
            ],
            "variations.*.image" => [
                "nullable",
                "max:255"
            ],
            "variations.*.price_import" => [
                "required"
            ],
            "variations.*.price_export" => [
                "required"
            ],
            "variations.*.status" => [
                "required"
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $nameUrl = "update";
                $updateId = $getUrl == $nameUrl ? $rules["id"] : [];
                $updateIdAttribute = $getUrl == $nameUrl ? $rules["attributes.*.id"] : [];
                $updateIdAttributeValue = $getUrl == $nameUrl ? $rules['attributes.*.attribute_values.*.id'] : [];
                $updateIdVariation = $getUrl == $nameUrl ? $rules['variations.*.id'] : [];
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
                    "status" => $rules["status"],
                    "attributes" => $rules['attributes'],
                    "attributes.*.id" => $updateIdAttribute,
                    "attributes.*.name" => $rules['attributes.*.name'],
                    "attributes.*.attribute_values" => $rules['attributes.*.attribute_values'],
                    "attributes.*.attribute_values.*.id" => $updateIdAttributeValue,
                    "attributes.*.attribute_values.*.value" => $rules['attributes.*.attribute_values.*.value'],
                    "variations" => $rules['variations'],
                    "variations.*.id" => $updateIdVariation,
                    "variations.*.sku" => $rules['variations.*.sku'],
                    "variations.*.barcode" => $rules['variations.*.barcode'],
                    "variations.*.variation_name" => $rules['variations.*.variation_name'],
                    "variations.*.display_name" => $rules['variations.*.display_name'],
                    "variations.*.image" => $rules['variations.*.image'],
                    "variations.*.price_import" => $rules['variations.*.price_import'],
                    "variations.*.price_export" => $rules['variations.*.price_export'],
                    "variations.*.status" => $rules['variations.*.status']
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
