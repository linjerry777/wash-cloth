<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\LaundryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * 顯示客戶儀表板（訂單列表）
     */
    public function customerDashboard()
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->with(['items.laundryType'])
            ->latest()
            ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'pending_orders' => $orders->where('status', Order::STATUS_PENDING)->count(),
            'processing_orders' => $orders->where('status', Order::STATUS_PROCESSING)->count(),
            'completed_orders' => $orders->where('status', Order::STATUS_COMPLETED)->count(),
        ];

        return Inertia::render('Customer/Dashboard', [
            'orders' => $orders,
            'stats' => $stats
        ]);
    }

    /**
     * 顯示員工儀表板
     */
    public function employeeDashboard()
    {
        $orders = Order::with(['user', 'items.laundryType'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
            'processing_orders' => Order::where('status', Order::STATUS_PROCESSING)->count(),
            'completed_orders' => Order::where('status', Order::STATUS_COMPLETED)->count(),
        ];

        return Inertia::render('Employee/Dashboard', [
            'orders' => $orders,
            'stats' => $stats
        ]);
    }

    /**
     * 搜索客戶
     */
    public function searchCustomers(Request $request)
    {
        $query = $request->input('query');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $customers = User::where('role_id', 3)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                })
                ->select('id', 'name', 'email', 'phone')
                ->limit(5)
                ->get();

            return response()->json($customers);
        } catch (\Exception $e) {
            return response()->json(['error' => '搜索失敗：' . $e->getMessage()], 500);
        }
    }

    /**
     * 顯示新增訂單頁面（僅限員工）
     */
    public function create()
    {
        return Inertia::render('Employee/CreateOrder', [
            'laundryTypes' => LaundryType::all()
        ]);
    }

    /**
     * 建立訂單（僅限員工）
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.laundry_type_id' => ['required', 'exists:laundry_types,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $request->user_id,
                'status' => Order::STATUS_PENDING,
                'notes' => $request->notes,
                'total_price' => 0,
            ]);

            $totalPrice = 0;
            foreach ($request->items as $item) {
                $laundryType = LaundryType::find($item['laundry_type_id']);
                $itemPrice = $laundryType->price * $item['quantity'];
                $totalPrice += $itemPrice;

                $order->items()->create([
                    'laundry_type_id' => $item['laundry_type_id'],
                    'quantity' => $item['quantity'],
                    'price' => $laundryType->price,
                    'subtotal' => $itemPrice,
                ]);
            }

            $order->update(['total_price' => $totalPrice]);
            DB::commit();

            return redirect()->route('employee.orders.index')
                ->with('success', '訂單已成功創建');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '創建訂單失敗：' . $e->getMessage());
        }
    }

    /**
     * 顯示訂單管理頁面（僅限員工）
     */
    public function index()
    {
        $orders = Order::with(['user', 'items.laundryType'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Employee/Orders/Index', [
            'orders' => $orders
        ]);
    }

    /**
     * 更新訂單狀態（僅限員工）
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:' . implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_PROCESSING,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ])],
        ]);

        $order->update([
            'status' => $request->status,
            'completed_at' => $request->status === Order::STATUS_COMPLETED ? now() : null,
        ]);

        return back()->with('success', '訂單狀態已更新');
    }

    /**
     * 顯示訂單詳情
     */
    public function show(Order $order)
    {
        $user = Auth::user();

        // 確保客戶只能查看自己的訂單
        if ($user->role_id === 3 && $order->user_id !== $user->id) {
            abort(403, '未授權的訪問');
        }

        $order->load(['user', 'items.laundryType']);

        return Inertia::render('Orders/Show', [
            'order' => $order
        ]);
    }

    /**
     * 顯示客戶新增訂單頁面
     */
    public function customerCreate()
    {
        return Inertia::render('Customer/NewOrder', [
            'laundryTypes' => LaundryType::all()
        ]);
    }

    /**
     * 客戶建立訂單
     */
    public function customerStore(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.laundry_type_id' => ['required', 'exists:laundry_types,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => Order::STATUS_PENDING,
                'notes' => $request->notes,
                'total_price' => 0,
            ]);

            $totalPrice = 0;
            foreach ($request->items as $item) {
                $laundryType = LaundryType::find($item['laundry_type_id']);
                $itemPrice = $laundryType->price * $item['quantity'];
                $totalPrice += $itemPrice;

                $order->items()->create([
                    'laundry_type_id' => $item['laundry_type_id'],
                    'quantity' => $item['quantity'],
                    'price' => $laundryType->price,
                    'subtotal' => $itemPrice,
                ]);
            }

            $order->update(['total_price' => $totalPrice]);
            DB::commit();

            return redirect()->route('customer.my-orders')
                ->with('success', '訂單已成功創建');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '創建訂單失敗：' . $e->getMessage());
        }
    }

    /**
     * 顯示客戶的訂單列表
     */
    public function customerOrders()
    {
        $orders = Auth::user()->orders()
            ->with(['items.laundryType'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Customer/Orders', [
            'orders' => $orders
        ]);
    }
}
