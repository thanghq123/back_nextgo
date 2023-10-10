<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PricingRequest extends FormRequest
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
                'name' => 'required|unique:App\Models\Pricing,name',
                'max_locations' => 'required',
                'max_users' => 'required',
                'price_per_month' => 'required',
            ];
        }

        if($url == "update"){
            return [
                'name' => 'required|unique:App\Models\Pricing,name,'.$this->id,
                'max_locations' => 'required',
                'max_users' => 'required',
                'price_per_month' => 'required',
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            "name.required" => "Không được để trống!",
            "name.unique" => "Tên đã tồn tại!",
            "max_locations.required" => "Số chi nhánh tối đa không được để trống",
            "max_users.required" => "Số người dùng tối đa không được để trống",
            "price_per_month.required" => "Giá/tháng không được để trống",
        ];
    }
}
