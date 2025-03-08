<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // 獲取客戶角色
        $customerRole = Role::where('slug', 'customer')->first();

        if (!$customerRole) {
            return response()->json(['message' => '系統錯誤，角色不存在'], 500);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $customerRole->id,
        ]);

        return response()->json([
            'message' => '註冊成功',
            'user' => $user
        ], 201);
    }
}
