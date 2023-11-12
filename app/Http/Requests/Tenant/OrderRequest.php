<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class OrderRequest extends FormRequest
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
        $rules =  [
            "id" => [
                "required",
                "exists:App\Models\Tenant\Order,id"
            ],
            "location_id" => [
                "required",
                "exists:App\Models\Tenant\Location,id"
            ],
            "customer_id" => [
                "required",
                "exists:App\Models\Tenant\Customer,id"
            ],
            "created_by" => [
                "required",
                "exists:App\Models\Tenant\User,id"
            ],
            "discount" => [
                "nullable",
                "gte:0"
            ],
            "discount_type" => "nullable",
            "tax" => [
                "nullable",
                "gte:0"
            ],
            "service_charge" => [
                "nullable",
                "gte:0"
            ],
            "total_product" => [
                "nullable",
                "gt:0"
            ],
            "total_price" => [
                "nullable",
                "gt:0"
            ],
            "status" => "required",
            "payment_status" => "required",
            "order_details" => "required",
            "order_details.*.variation_id" => [
                "required",
                "exists:App\Models\Tenant\Variation,id"
            ],
            "order_details.*.batch_id" => [
                "nullable",
                "exists:App\Models\Tenant\Batch,id"
            ],
            "order_details.*.discount" => [
                "nullable",
                "gte:0"
            ],
            "order_details.*.discount_type" => "nullable",
            "order_details.*.tax" => [
                "nullable",
                "gte:0"
            ],
            "order_details.*.quantity" => [
                "required",
                "gt:0"
            ],
            "order_details.*.total_price" => [
                "required",
                "gt:0"
            ],
        ];

        switch ($getUrl){
            case "store":
                return [
                    "location_id" => $rules['location_id'],
                    "customer_id" => $rules['customer_id'],
                    "created_by" => $rules['created_by'],
                    "discount" => $rules['discount'],
                    "discount_type" => $rules['discount_type'],
                    "tax" => $rules['tax'],
                    "service_charge" => $rules['service_charge'],
                    "total_product" => $rules['total_product'],
                    "total_price" => $rules['total_price'],
                    "status" => $rules['status'],
                    "order_details" => $rules['order_details'],
                    "payment_status" => $rules['payment_status'],
                    "order_details.*.variation_id" => $rules['order_details.*.variation_id'],
                    "order_details.*.batch_id" => $rules['order_details.*.batch_id'],
                    "order_details.*.discount" => $rules['order_details.*.discount'],
                    "order_details.*.discount_type" => $rules['order_details.*.discount_type'],
                    "order_details.*.tax" => $rules['order_details.*.tax'],
                    "order_details.*.quantity" => $rules['order_details.*.quantity'],
                    "order_details.*.total_price" => $rules['order_details.*.total_price']
                ];
            case "show":
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
            "max" => "Bạn đã vượt quá ký tự cho phép!",
            "gt" => "Dữ liệu không hợp lệ!",
            "gte" => "Dữ liệu không hợp lệ!"
        ];
    }
}
