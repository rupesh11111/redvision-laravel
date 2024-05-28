<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            DB::beginTransaction();
            $validator = Validator::make(request()->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first()
                ], 412);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user'
            ]);

            $token = JWTAuth::fromUser($user);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Registered Successfully', 'data' => ['token' => $token, 'user' => $user]], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]], 400);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make(request()->all(), [
                'email' => 'required|string|email|max:255|exists:users,email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], 412);
            }

            $user = User::whereEmail(request('email'))->first();

            if (!Hash::check(request('password'), $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials'
                ], 401);
            }
            $token = JWTAuth::fromUser($user);
            return response()->json(['status' => true, 'message' => 'Logged in Successfully', 'data' => ['token' => $token, 'user' => $user]], 200);
        } catch (Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'data' => [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]], 400);
        }
    }

    public function logout()
    {

        return JWTAuth::parseToken()->invalidate( true );

        return response()->json(['message' => 'Successfully logged out']);
    }
}
