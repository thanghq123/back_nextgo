<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Role;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function __construct(
        private User $model,
    )
    {
    }

    public function list()
    {
        try {
            $user = request()->user();
            $userQuery = $this->model::with(['location', 'roles'])
                ->where('id', '!=', $user->id);
            if ($user->hasRole('admin')) {
                $userQuery->where('location_id', $user->location_id);
            }
            $users = $userQuery->get();
            return responseApi($users, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            return responseApi($this->model::with('roles')->find($request->id), true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
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
        $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        $user->location_id = $request->location_id;
        $user->username = !$request->username ? null : $request->username;
        $user->tel = !$request->tel ? null : $request->tel;
        $user->status = 1;
        $user->created_by = $request->user()->id ?? null;
        $user->save();
        $user->syncRoles($role->name);
        return responseApi('Tạo tài khoản admin thành công', true);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:App\Models\Tenant\User,email,' . $request->id
            ],
            'password' => 'nullable',
            'location_id' => 'required',
        ]);
        if ($validator->fails()) return responseApi($validator->errors());
        $user = $this->model::query()->findOrFail($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->email_verified_at = now();
        if ($request->password) {
            $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        }
        $user->location_id = $request->location_id;
        $user->username = !$request->username ? null : $request->username;
        $user->tel = !$request->tel ? null : $request->tel;
        $user->status = !$request->status ? 0 : $request->status;
        $user->save();
        if (isset($request->role_id)) {
            $role = Role::query()->findOrFail($request->role_id);
            $user->syncRoles($role->name);
        }
        return responseApi('Cập nhật tài khoản thành công', true);
    }

    public function delete(Request $request)
    {
        $user = $this->model::query()->findOrFail($request->id);
        $user?->removeRole($user->getRoleNames()[0])->delete();
        return responseApi('Xóa tài khoản thành công', true);
    }
}
