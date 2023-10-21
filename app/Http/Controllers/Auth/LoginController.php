<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $login = User::query()
                ->where('email', $request->email)
                ->where('password', $request->password)->first();
            if ($login) {
                auth()->login($login,true);
                return response()->json('success');
            } else {
                return response()->json('fail');
            }
        }
        return view('auth.login');
    }
}
