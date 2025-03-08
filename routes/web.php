<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaundryTypeController;
use App\Http\Controllers\OrderController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // 共用儀表板頁面
    Route::get('/dashboard', function () {
        // 根據角色重定向到不同的儀表板
        if (auth()->user()->role_id == 1) { // 管理員
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role_id == 2) { // 員工
            return redirect()->route('employee.dashboard');
        } else { // 客戶
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');

    // 客戶路由 (role_id = 3)
    Route::middleware('role:3')->prefix('customer')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Customer/Dashboard');
        })->name('customer.dashboard');

        Route::get('/new-order', function () {
            return Inertia::render('Customer/NewOrder', [
                'laundryTypes' => \App\Models\LaundryType::all()
            ]);
        })->name('customer.new-order');

        Route::get('/my-orders', [OrderController::class, 'customerOrders'])
            ->name('customer.my-orders');
    });

    // 員工路由 (role_id = 2)
    Route::middleware('role:2')->prefix('employee')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Employee/Dashboard');
        })->name('employee.dashboard');

        Route::get('/manage-orders', [OrderController::class, 'manageOrders'])
            ->name('employee.manage-orders');
    });

    // 管理員路由 (role_id = 1)
    Route::middleware('role:1')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');

        Route::get('/laundry-types', [LaundryTypeController::class, 'adminIndex'])
            ->name('admin.laundry-types');

        Route::get('/users', function () {
            return Inertia::render('Admin/Users', [
                'users' => \App\Models\User::with('role')->get(),
                'roles' => \App\Models\Role::all()
            ]);
        })->name('admin.users');
    });

    // 用戶資料頁面
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
