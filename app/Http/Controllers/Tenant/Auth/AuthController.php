<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'location_id' => $location->id ?? null,
            'inventory_id' => $inventory->id ?? null,
            'domain_name' => $tenant->name ?? null,
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
            'location_id' => $location->id ?? null,
            'inventory_id' => $inventory->id ?? null,
            'domain_name' => Tenant::current()->name ?? null,
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
        $filterDatabase = Tenant::where('database', $request->name_tenant)->get();
        if (count($filterDatabase) > 0) return responseApi('Cơ sở đã tổn tại');
        $tenant = new Tenant();
        $tenant->business_name = $request->business_name;
        $tenant->address = $request->address;
        $tenant->name = $request->name_tenant;
        $tenant->domain = $request->name_tenant . ".com";
        $tenant->database = $request->name_tenant;
        $tenant->user_id = $request->user_id;
        $tenant->business_field_id = $request->business_field;
        $tenant->pricing_id = 1;
        $tenant->due_at = Carbon::now()->addDays(14)->format('Y-m-d');
        $tenant->status = 1;
        $tenant->save();
        return responseApi('Tạo chi nhánh thành công', true);
    }

    public function createAdminForTenant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:App\Models\Tenant\User,email'
            ],
            'password' => 'required',
            'location_id' => 'required'
        ]);
        if ($validator->fails()) return responseApi($validator->errors());
        $user = new Tenant\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->password = password_hash($request->password,PASSWORD_DEFAULT);
        $user->location_id = $request->location_id;
        $user->username = !$request->username ? null : $request->username;
        $user->tel = !$request->tel ? null : $request->tel;
        $user->status = 1;
        $user->created_by = !$request->created_by ? null : $request->created_by;
        $user->save();
        $user->syncRoles('admin');
        return responseApi('Tạo tài khoản admin thành công',true);
    }
    public function updateAdminForTenant(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:App\Models\Tenant\User,email,' . $request->id
            ],
            'password' => 'required',
            'location_id' => 'required'
        ]);
        if ($validator->fails()) return responseApi($validator->errors());
        $user = Tenant\User::query()->findOrFail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->email_verified_at = now();
        $user->password = password_hash($request->password,PASSWORD_DEFAULT);
        $user->location_id = $request->location_id;
        $user->username = !$request->username ? null : $request->username;
        $user->tel = !$request->tel ? null : $request->tel;
        $user->status = 1;
        $user->created_by = !$request->created_by ? null : $request->created_by;
        $user->save();
        return responseApi('Cập nhật tài khoản thành công',true);
    }
    public function deleteAdminForTenant(Request $request){
        $user = Tenant\User::query()->findOrFail($request->id);
        $user?->removeRole('admin')->delete();
        return responseApi('Xóa tài khoản thành công',true);
    }
}
