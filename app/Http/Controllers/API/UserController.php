<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 獲取當前登入用戶資訊
     */
    public function currentUser(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => $user,
            'role' => $user->role
        ]);
    }

    /**
     * 獲取所有客戶 (僅限員工使用)
     */
    public function customers(Request $request)
    {
        $customerRole = \App\Models\Role::where('slug', 'customer')->first();

        $customers = User::where('role_id', $customerRole->id)
            ->withCount('orders')
            ->latest()
            ->paginate(15);

        return response()->json($customers);
    }
}
