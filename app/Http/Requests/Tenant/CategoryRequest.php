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
        $url = Str::after($this->url(), 'tenant/api/v1/categories/');

        if($url == "store"){
            return [
                'name' => 'required|unique:App\Models\Tenant\Category,name'
            ];
        }

        if($url == "update"){
            return [
                'name' => 'required|unique:App\Models\Tenant\Category,name,'.$this->id,
            ];
        }

        return [];
    }

     public function messages()
     {
         return [
             "name.required" => "Không được để trống!",
             "name.unique" => "Tên đã tồn tại!"
         ];
     }
}
