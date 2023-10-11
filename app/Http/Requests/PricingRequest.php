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
                'name' => 'required|min:3|unique:App\Models\Pricing,name',
                'max_locations' => 'required|numeric|min:0',
                'max_users' => 'required|numeric|min:0',
                'price_per_month' => 'required|numeric|min:0',
            ];
        }

        if($url == "update"){
            return [
                'name' => 'required|min:3|unique:App\Models\Pricing,name,'.$this->id,
                'max_locations' => 'required|numeric|min:0',
                'max_users' => 'required|numeric|min:0',
                'price_per_month' => 'required|numeric|min:0',
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
            "max_locations.numeric" => "Số chi nhánh tối đa phải là số",
            "max_users.numeric" => "Số người dùng tối đa phải là số",
            "price_per_month.numeric" => "Giá/tháng phải là số",
            "max_locations.min" => "Số chi nhánh tối đa phải lớn hơn 0",
            "max_users.min" => "Số người dùng tối đa phải lớn hơn 0",
            "price_per_month.min" => "Giá/tháng phải lớn hơn 0",
        ];
    }
}
