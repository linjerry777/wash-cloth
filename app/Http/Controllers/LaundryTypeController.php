<?php

namespace App\Http\Controllers;

use App\Models\LaundryType;
use Illuminate\Http\Request;

class LaundryTypeController extends Controller
{
    /**
     * 獲取所有洗衣類型
     */
    public function index()
    {
        $laundryTypes = LaundryType::all();
        return response()->json($laundryTypes);
    }

    // 其他方法（按需實現）
}
