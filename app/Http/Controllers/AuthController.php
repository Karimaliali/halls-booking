<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'role' => 'required|string|in:admin,owner,customer'
        ]);
   

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'role' => $fields['role']
        ]);

        $token = $user->createToken('main')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }

        $token = $user->createToken('main')->plainTextToken;
        return response(['user' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'تم تسجيل الخروج بنجاح'], 200);
    }

    public function deleteAccount(Request $request) {
        $user = $request->user();
        $user->tokens()->delete(); 
        $user->delete();

        return response(['message' => 'تم حذف الحساب وجميع البيانات المتعلقة به بنجاح'], 200);
    }
}