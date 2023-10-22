<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
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
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['data' => $user]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        /* ------------ Create a new personal access token for the user. ------------ */
        $token = auth()->user()->createToken('authToken')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function getUser()
    {
        return response()->json([
            'data' => dd(User::all())
        ]);
    }

    public function logout()
    {
        $token = auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

}
