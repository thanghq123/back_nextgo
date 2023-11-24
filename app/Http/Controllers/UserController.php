<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct(
        protected UserRequest $request
    )
    {
    }

    public function index()
    {
        $users = User::with(['tenants', 'parent'])->orderBy('created_at', 'desc')->get();
        return view('admin.user.index', compact('users'));
    }

    public function show()
    {
        try {
            if ($this->request->show_id) $user = User::with(['tenants.business_field', 'parent'])->find($this->request->show_id);
            elseif ($this->request->update_id) $user = User::query()->find($this->request->update_id);
            if (!$user) return responseApi("không tồn tại!");
            return responseApi($user, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete()
    {
        try {
            $tenant = User::query()->findOrFail($this->request->id);
            $tenant?->tenants()->delete();
            $tenant?->delete();
            return responseApi('Xoá thành công', true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage());
        }
    }

    public function trash()
    {
        $users = User::with(['tenants'])->onlyTrashed()->get();
        return view('admin.user.trash', compact('users'));
    }

    public function restore()
    {
        $user = User::whereId($this->request->id);
        $user?->restore();
        Tenant::withTrashed()->where('user_id', $this->request->id)->restore();
        return responseApi('Thành công', true);
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $this->request->input('ten_user');
            $user->email = $this->request->input('email_user');
            $user->password = password_hash($this->request->input('password'), PASSWORD_DEFAULT);
            $user->tel = $this->request->input('phone_number') ? $this->request->input('phone_number') : null;
            $user->created_by = auth()->id();
            $user->save();
            DB::commit();
            return responseApi("Tạo thành công", true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi("Tạo thất bại");
        }
    }
    public function update(){
        return responseApi($this->request->url(),true);
        DB::beginTransaction();
        try {
            $user = User::query()->find($this->request->id);
            if(!$user) return responseApi('User không tồn tại');
            $user->name = $this->request->input('ten_user');
            $user->email = $this->request->input('email_user');
            $user->password = password_hash($this->request->input('password'), PASSWORD_DEFAULT);
            $user->tel = $this->request->input('phone_number') ? $this->request->input('phone_number') : null;
            $user->created_by = auth()->id();
            $user->save();
            DB::commit();
            return responseApi("Cập nhật thành công", true);
        } catch (\Throwable $throwable) {
            DB::rollBack();
            return responseApi("Cập nhật thất bại");
        }
    }
}
