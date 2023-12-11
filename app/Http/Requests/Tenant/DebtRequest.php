<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class DebtRequest extends FormRequest
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
                "exists:App\Models\Tenant\Debt,id"
            ],
            "partner_id" => [
                "required"
            ],
            "partner_type" => [
                "required",
                "in:0,1"
            ],
            "type" => [
                "required",
                "in:0,1"
            ],
            "name" => [
                "required"
            ],
            "amount_debt" => [
                "required",
                "numeric",
                "gt:0"
            ],
            "note" => [
                "max:500",
                "nullable"
            ],
            "status" => [
                "in:0,1,2,3",
                "nullable"
            ],
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                return [
                    "id" => $updateId,
                    "partner_id" => $rules['partner_id'],
                    "partner_type" => $rules['partner_type'],
                    "type" => $rules['type'],
                    "name" => $rules['name'],
                    "amount_debt" => $rules['amount_debt'],
                    "note" => $rules['note'],
                    "status" => $rules['status']
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
            "numeric" => "Chỉ được nhập số!",
            "gt" => "Phải lớn hơn 0!",
        ];
    }
}
