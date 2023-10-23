<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Tenant\User;
use Carbon\Carbon;

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
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return responseApi($user, true);
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
        if (!Auth::guard('api')->attempt($credentials)) {
            return responseApi('Unauthorized', false);
        }
        /* ------------ Create a new personal access token for the user. ------------ */
        $token = Auth::guard('api')->user()->createToken('authToken')->plainTextToken;
        $data = [
            'user' => Auth::guard('api')->user(),
            'token' => $token,
            'token_type' => 'Bearer',
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
