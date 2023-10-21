<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class InventoryTransactionRequest extends FormRequest
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
        $rules = [
            "inventory_id" => [
                "required",
            ],
            "partner_id" => [
                "required",
            ],
            "partner_type" => [
                "required",
            ],
            "trans_type" => [
                "required",
            ],
            "reason" => [
                "required",
            ],
            "note" => [
                "required",
            ],
            "status" => [
                "required",
            ],
            "created_by" => [
                "required",
            ],
            "inventory_transaction_details" => [
                "required",
            ],
            "inventory_transaction_details.*.variation_id" => [
                "required",
            ],
            "inventory_transaction_details.*.batch_id" => [
                "required",
            ],
            "inventory_transaction_details.*.price" => [
                "required",
            ],
            "inventory_transaction_details.*.price_type" => [
                "required",
            ],
            "inventory_transaction_details.*.quantity" => [
                "required",
            ],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            "required" => "Không được để trống!",
            "exists" => "Dữ liệu không tồn tại!",
            "unique" => "Dữ liệu đã tồn tại!",
            "max" => "Bạn đã vượt quá ký tự cho phép!"
        ];
    }
}
