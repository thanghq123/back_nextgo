<?php

namespace App\Http\Requests\Tenant;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class GroupCustomerRequest extends FormRequest
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
        $url = Str::afterLast($this->url(), '/');

        if($url == "store"){
            return [
                "name" => [
                    "required",
                    "unique:App\Models\Tenant\GroupCustomer,name"
                ],
                "description" => [
                    "required",
                    "max:1000"
                ]
            ];
        }

        if($url == "update"){
            return [
                "name" => [
                    "required",
                    "unique:App\Models\Tenant\GroupCustomer,name,".$this->id
                ],
                "description" => [
                    "required",
                    "max:1000"
                ]
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            "name.required" => "Không được để trống!",
            "name.unique" => "Tên đã tồn tại!",
            "description.required" => "Không được để trống!",
            "description.max" => "Bạn đã vượt quá ký tự cho phép!"
        ];
    }
}
