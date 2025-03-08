<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaundryTypeController;
use App\Http\Controllers\OrderController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    // 共用儀表板頁面
    Route::get('/dashboard', function () {
        $user = Auth::user();
        // 根據角色重定向到不同的儀表板
        if ($user->role_id == 1) { // 管理員
            return redirect()->route('admin.dashboard');
        } elseif ($user->role_id == 2) { // 員工
            return redirect()->route('employee.dashboard');
        } else { // 客戶
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');

    // 客戶路由 (role_id = 3)
    Route::middleware('role:3')->prefix('customer')->group(function () {
        Route::get('/dashboard', [OrderController::class, 'customerDashboard'])->name('customer.dashboard');
        Route::get('/new-order', [OrderController::class, 'customerCreate'])->name('customer.new-order');
        Route::post('/orders', [OrderController::class, 'customerStore'])->name('customer.orders.store');
        Route::get('/my-orders', [OrderController::class, 'customerOrders'])->name('customer.my-orders');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('customer.orders.show');
    });

    // 員工路由 (role_id = 2)
    Route::middleware('role:2')->prefix('employee')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Employee/Dashboard');
        })->name('employee.dashboard');

        // 訂單管理路由
        Route::get('/orders', [OrderController::class, 'index'])->name('employee.orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('employee.orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('employee.orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('employee.orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('employee.orders.update-status');
        Route::get('/customers/search', [OrderController::class, 'searchCustomers'])->name('employee.customers.search');
    });

    // 管理員路由 (role_id = 1)
    Route::middleware('role:1')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('admin.dashboard');

        Route::resource('laundry-types', LaundryTypeController::class);
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

require __DIR__ . '/auth.php';
