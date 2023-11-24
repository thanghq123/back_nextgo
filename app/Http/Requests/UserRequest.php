<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UserRequest extends FormRequest
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
        if ($url == 'store') {
            return [
                'email_user' => [
                    'required',
                    'email',
                    'unique:users'
                ],
                'password' => 'required',
                'ten_user' => 'required'
            ];
        }
        else if ($url == 'update') {
            return [
                'email_user' => [
                    'required',
                    'email',
                    'unique:users,email,'.$this->id
                ],
                'password' => 'required',
                'ten_user' => 'required'
            ];
        }
        return [
            //
        ];
    }

    public function messages()
    {
        return [
            'email_user.required' => 'email_user không được để trống',
            'email_user.unique' => 'email_user đã tồn tại',
            'email_user.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'ten_user.required' => 'Tên người dùng không được để trống'
        ];
    }
}
