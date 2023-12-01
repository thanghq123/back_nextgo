<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{

    function getUserEnterprise(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return responseApi($validator->errors(), false);
        }
        if (!Auth::guard('web')->attempt($credentials)) {
            $msg = "Thông tin email hoặc mật khẩu không chính xác";
            return responseApi(['password' => $msg], false);
        }
        /* ------------ Create a new personal access token for the user. ------------ */
        $user = Auth::guard('web')->user();
        $token = $user->createToken('authToken');
        $data = generateUserToken($user);
        return responseApi($data, true);
    }

    public function loginEnterprise(Request $request)
    {
        $user = \auth()->user();

        $tenant = Tenant::query()->where('name', $request->tenant_name)->firstOrFail();

        $tenant->makeCurrent();

        $tenantUser = Tenant\User::query()->where('email', $user->email)->first();

        $token = generateUserToken($tenantUser);

        $location = Tenant\Location::query()->first();

        $inventory = Tenant\Inventory::query()->where('location_id', $location->id)->first();

        $data = [
            'user' => $user,
            'token' => $token,
            'location' => $location ?? null,
            'inventory' => $inventory ?? null,
            'tenant' => $tenant ?? null,
        ];

        return responseApi($data, true);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return responseApi($validator->errors(), false);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();


        $data = generateUserToken($user);

        return responseApi($data, true);
    }

    public function login(Request $request)
    {
        if (!Tenant::checkCurrent()) {
            return responseApi(['domain_name' => "Địa chỉ doanh nghiệp không tồn tại"], false);
        }
        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|min:5'
        ]);
        if ($validator->fails()) {
            return responseApi($validator->errors(), false);
        }
        if (!Auth::guard('api')->attempt($credentials)) {
            $msg = "Thông tin email hoặc mật khẩu không chính xác";
            return responseApi(['password' => $msg], false);
        }
        /* ------------ Create a new personal access token for the user. ------------ */
        $user = Auth::guard('api')->user();

        $token = generateUserToken($user);

        $location = $user->location_id
            ? Tenant\Location::query()->find($user->location_id)
            : Tenant\Location::query()->first()->id;

        $inventory = Tenant\Inventory::query()->where('location_id', $location->id)->first();

        $data = [
            'user' => $user,
            'token' => $token,
            'location' => $location ?? null,
            'inventory' => $inventory ?? null,
            'tenant' => Tenant::current()->name ?? null,
        ];
        return responseApi($data, true);
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

}
