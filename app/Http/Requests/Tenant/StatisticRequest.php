<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StatisticRequest extends FormRequest
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
            "location" => [
                "nullable",
                "exists:App\Models\Tenant\Location,id"
            ],
            "inventory_id" => [
                "nullable",
                "exists:App\Models\Tenant\Inventory,id"
            ]
        ];

        switch ($getUrl){
            case "income":
            case "general":
            case "payment-methods":
            case "customers":
            case "products":
                return [
                    "location" => $rules["location"],
                    "inventory_id" => $rules["inventory_id"]
                ];
            default:
                return [];
        }
    }

    public function messages()
    {
        return [
            "exists" => "Dữ liệu không tồn tại!"
        ];
    }
}
