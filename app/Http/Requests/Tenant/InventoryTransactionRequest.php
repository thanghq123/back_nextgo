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
        $getUrl = Str::afterLast($this->url(), '/');
        $rules = [
            "inventory_id" => [
                "required",
            ],
            "inventory_id_out" => [
                "required",
            ],
            "partner_id" => [
                "required",
            ],
            "partner_type" => [
                "required",
            ],
            "reason" => [
                "required",
            ],
            "note" => [
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
        switch ($getUrl) {
            case "import/create":
            case "trans/store":
            $nameUrl = "trans/store";
            $createTransfer = $getUrl == $nameUrl ? $rules["inventory_id_out"] : [];
                return [
                    "inventory_id"=>$rules["inventory_id"],
                    "inventory_id_out"=>$createTransfer,
                    "partner_id"=>$rules["partner_id"],
                    "partner_type"=>$rules["partner_type"],
                    "reason"=>$rules["reason"],
                    "note"=>$rules["note"],
                    "created_by"=>$rules["created_by"],
                    "inventory_transaction_details"=>$rules["inventory_transaction_details"],
                    "inventory_transaction_details.*.variation_id"=>$rules["inventory_transaction_details.*.variation_id"],
                    "inventory_transaction_details.*.batch_id"=>$rules["inventory_transaction_details.*.batch_id"],
                    "inventory_transaction_details.*.price"=>$rules["inventory_transaction_details.*.price"],
                    "inventory_transaction_details.*.price_type"=>$rules["inventory_transaction_details.*.price_type"],
                    "inventory_transaction_details.*.quantity"=>$rules["inventory_transaction_details.*.quantity"],
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
            "unique" => "Dữ liệu đã tồn tại!",
            "max" => "Bạn đã vượt quá ký tự cho phép!"
        ];
    }
}
