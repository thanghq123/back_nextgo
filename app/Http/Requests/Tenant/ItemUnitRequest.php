<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class ItemUnitRequest extends FormRequest
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
        $url = Str::afterLast($this->url(), '/');

        if($url == "store"){
            return [
                "name" => [
                    "required",
                    "max:255",
                    "unique:App\Models\Tenant\ItemUnit,name"
                ],
            ];
        }

        if($url == "update"){
            return [
                "name" => [
                    "required",
                    "max:255",
                    "unique:App\Models\Tenant\ItemUnit,name,".$this->id
                ],
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            "name.required" => "Không được để trống!",
            "name.unique" => "Tên đã tồn tại!",
            "name.max" => "Bạn đã vượt quá ký tự cho phép!",
        ];
    }
}
