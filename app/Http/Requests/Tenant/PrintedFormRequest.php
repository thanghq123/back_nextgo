<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PrintedFormRequest extends FormRequest
{
    use TFailedValidation;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        $getUrl = Str::afterLast($this->url(), '/');
        $id = ",".$this->id;
        $rules =  [
            "id" => [
                "required",
                "exists:App\Models\Tenant\PrintedForm,id"
            ],
            "name" => [
                "required",
                "max:255",
                "unique" => "unique:App\Models\Tenant\PrintedForm,name"
            ],
            "form" => [
                "required",
            ]
        ];

        switch ($getUrl){
            case "store":
            case "update":
                $updateId = $getUrl == "update" ? $rules["id"] : [];

                $updateName = $getUrl == "update" ? [
                    $rules["name"],
                    $rules["name"]["unique"].$id
                ] :
                    $rules["name"];

                $updateForm = $getUrl == "update" ? [
                    $rules["form"],
                ] :
                    $rules["form"];

                return [
                    "id" => $updateId,
                    "name" => $updateName,
                    "form" => $updateForm
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
            "max" => "Bạn đã vượt quá ký tự cho phép!"
        ];
    }
}
