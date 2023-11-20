<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tenant;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['tenants'])->orderBy('created_at','desc')->get();
        return view('admin.user.index', compact('users'));
    }

    public function show(Request $request)
    {
        try {
            $user = User::with('pricing', 'tenants.business_field')->where('id', $request->id)->first();
            if (!$user) return responseApi("không tồn tại!");
            return responseApi($user, true);
        } catch (\Throwable $throwable) {
            return responseApi($throwable->getMessage(), false);
        }
    }

    public function delete(Request $request)
    {
        try {
            $tenant = User::query()->findOrFail($request->id);
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

    public function restore(Request $request)
    {
        $user = User::whereId($request->id);
        $user?->restore();
        Tenant::withTrashed()->where('user_id', $request->id)->restore();
        return responseApi('Thành công', true);
    }
}
