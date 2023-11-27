<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailForgotPassword;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Tenant\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:App\Models\Tenant\User,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return responseApi($validator->errors(), false);
        }
        DB::beginTransaction();
        try {
            $email = \App\Models\User::query()->whereEmail($request->email)->first();
            if (!$email) {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = password_hash($request->password, PASSWORD_DEFAULT);
                $user->save();
                DB::commit();
                return responseApi('Đăng ký thành công', true);
            } else {
                return responseApi('Email đã được đăng ký');
            }

        } catch (\Throwable $throwable) {
            DB::rollBack();
            return response()->json($throwable->getMessage());
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return responseApi($validator->errors(), false);
        }
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            auth()->login($user, true);
            return responseApi('Đăng nhập thành công', true);
        } else {
            return responseApi('Thông tin đăng nhập không đúng', true);
        }
    }

    public function getUser()
    {
        return response()->json([
            'data' => dd(User::all())
        ]);
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function createTenant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name_tenant' => 'required',
            'business_field' => 'required',
            'address' => 'required',
            'business_name' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) return responseApi($validator->errors());
        $filterDatabase = Tenant::where('database', $request->input('name_tenant'))->get();
        if (count($filterDatabase) > 0) return responseApi('Cơ sở đã tổn tại');
        $tenant = new Tenant();
        $tenant->business_name = $request->input('business_name');
        $tenant->address = $request->input('address');
        $tenant->name = $request->input('name_tenant');
        $tenant->domain = $request->input('name_tenant') . ".com";
        $tenant->database = $request->input('name_tenant');
        $tenant->user_id = $request->input('user_id');
        $tenant->business_field_id = $request->input('business_field');
        $tenant->pricing_id = 1;
        $tenant->due_at = Carbon::now()->addDays(14)->format('Y-m-d');
        $tenant->status = 1;
        $tenant->save();
        return responseApi('Tạo chi nhánh thành công', true);
    }

    public function forgotPass(Request $request)
    {
        $email = \App\Models\User::whereEmail($request->email)->first();
        if (!$email) {
            return responseApi('Email not found !');
        } else {
            $data = [
                'email' => $request->email,
                'token' => Str::random(15),
                'created_at' => now()
            ];
            $findEmail = DB::table('password_reset_tokens')
                ->where('email', $data['email']);
            if (!$findEmail->first()) {
                $findEmail->insert($data);
            }
            $mailData = [
                'token' => $findEmail->first()->token,
                'email' => $findEmail->first()->email
            ];
            Mail::to($mailData['email'])->send(new MailForgotPassword($mailData));
            return responseApi('Email đã được gửi. Vui lòng kiểm tra email trong hòm thư!', true);
        }
    }

    public function changePassword(Request $request)
    {
        $findEmailAndToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();
        session()->put('email', $request->email);
        if ($findEmailAndToken) {
            \App\Models\User::query()->where('email', session('email'))->update([
                'password' => password_hash($request->password, PASSWORD_DEFAULT)
            ]);
            session()->forget('email');
            return responseApi('Thay đổi mật khẩu thành công');
        } else {
            abort(404);
        }
    }

}
