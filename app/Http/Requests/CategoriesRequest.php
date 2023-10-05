<?php

namespace App\Http\Requests;

use App\Traits\TFailedValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CategoriesRequest extends FormRequest
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
        $url = Str::after($this->url(), 'tenant/api/v1/');
        if($url == "category/store" || $url == "category/update"){
            return [
                'name' => 'required'
            ];
        }

        return [];
    }

     public function messages()
     {
         return [
             "name.required" => "Không được để trống!"
         ];
     }
}
