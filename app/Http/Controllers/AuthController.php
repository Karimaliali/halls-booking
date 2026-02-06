<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function login(Request $request) {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response(['message' => 'بيانات الدخول غير صحيحة'], 401);
        }

        $token = $user->createToken('main')->plainTextToken;
        return response(['user' => $user, 'token' => $token]);
    }
}