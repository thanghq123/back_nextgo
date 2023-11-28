<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Role;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function list()
    {
        try {
            $users = User::with(['locations.district', 'locations.commune', 'locations.province'])->get();
            return responseApi($users, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:App\Models\Tenant\User,email'
            ],
            'password' => 'required',
            'location_id' => 'required',
            'role_id' => 'required'
        ]);
        if ($validator->fails()) return responseApi($validator->errors());
        $role = Role::query()->findOrFail($request->role_id);
        $user = new User();
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
        $user->syncRoles($role->name);
        return responseApi('Tạo tài khoản admin thành công',true);
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:App\Models\Tenant\User,email,' . $request->id
            ],
            'password' => 'required',
            'location_id' => 'required',
        ]);
        if ($validator->fails()) return responseApi($validator->errors());
        $user = User::query()->findOrFail($request->id);
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
        if(isset($request->role_id)){
            $role = Role::query()->findOrFail($request->role_id);
            $user->syncRoles($role->name);
        }
        return responseApi('Cập nhật tài khoản thành công',true);
    }
    public function delete(Request $request){
        $user = User::query()->findOrFail($request->id);
        $user?->removeRole($user->getRoleNames()[0])->delete();
        return responseApi('Xóa tài khoản thành công',true);
    }
}
