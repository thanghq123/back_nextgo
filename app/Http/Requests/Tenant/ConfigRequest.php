<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ConfigRequest extends FormRequest
{
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
                "exists:App\Models\Tenant\Config,id"
            ],
            "business_name" => [
                "required",
                "max:255",
            ],
            "email" => [
                "regex" => "regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/",
                "max" => "max:255",
                "unique" => "unique:App\Models\Tenant\Config,email",
                "nullable"
            ],
            "tel"=>[
                "regex" => "regex:/^(03|05|07|08|09)+([0-9]{8})$/",
                "min" => "min:10",
                "nullable"
            ],
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];
                $updateEmail = $getUrl == "update" ? [
                    $rules["email"],
                    $rules["email"]["unique"].$id
                ] :
                    $rules["email"];
                return [
                    "id" => $updateId,
                    "business_name" => $rules["business_name"],
                    "email" => $updateEmail,
                    "tel" => $rules["tel"]
                ];
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
            "max" => "Bạn đã vượt quá ký tự cho phép!"
        ];
    }
}
