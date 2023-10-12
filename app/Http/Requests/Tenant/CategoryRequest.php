<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CategoryRequest extends FormRequest
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

        switch ($getUrl){
            case "store":
                return [
                    "name" => [
                        "required",
                        "max:255",
                        "unique:App\Models\Tenant\Category,name"
                    ],
                ];
            case "update":
                return [
                    "id" => [
                        "required",
                        "exists:App\Models\Tenant\Category,id"
                    ],
                    "name" => [
                        "required",
                        "max:255",
                        "unique:App\Models\Tenant\Category,name," . $this->id
                    ],
                ];
            case "show":
            case "delete":
                return [
                    "id" => [
                        "required",
                        "exists:App\Models\Tenant\Category,id"
                    ]
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
