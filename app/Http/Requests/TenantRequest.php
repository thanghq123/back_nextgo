<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class TenantRequest extends FormRequest
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
        if($url == 'store'){
            if($this->user_id){
                return [
                    'name_tenant' => 'required',
                    'business_field' => 'required',
                    'user_id' => 'required'
                ];
            }else{
                return [
                    'username' => 'required',
                    'email' => 'required|email',
                    'password' => 'required'
                ];
            }
        }
        return [];
    }
    public function messages()
    {
        return [
            'name.required' => 'Tên phải được nhập',
            'business_field.required' => 'Lĩnh vực kinh doanh phải được chọn',
            'user_id.required' => 'Người dùng phải được chọn',
            'username.required' => 'Tên người dùng phải được nhập',
            'email.required' => 'Email phải được nhập',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Mật khẩu phải được nhập'
        ];
    }
}
