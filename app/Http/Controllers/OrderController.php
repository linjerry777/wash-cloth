<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\LaundryType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * 獲取訂單列表
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isEmployee()) {
            // 員工可以看到所有訂單
            $orders = Order::with(['user', 'items.laundryType'])
                ->latest()
                ->paginate(10);
        } else {
            // 客戶只能看到自己的訂單
            $orders = Order::where('user_id', $user->id)
                ->with(['items.laundryType'])
                ->latest()
                ->paginate(10);
        }

        return response()->json($orders);
    }

    /**
     * 顯示特定訂單
     */
    public function show(Order $order, Request $request)
    {
        $user = $request->user();

        // 確保客戶只能查看自己的訂單
        if (!$user->isEmployee() && $order->user_id !== $user->id) {
            return response()->json(['message' => '未授權的訪問'], 403);
        }

        $order->load(['user', 'items.laundryType']);

        return response()->json($order);
    }

    /**
     * 建立訂單
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.laundry_type_id' => 'required|exists:laundry_types,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $totalPrice = 0;
        $itemsData = [];

        // 計算總價並準備項目資料
        foreach ($validated['items'] as $item) {
            $laundryType = LaundryType::findOrFail($item['laundry_type_id']);
            $price = $laundryType->price * $item['quantity'];
            $totalPrice += $price;

            $itemsData[] = [
                'laundry_type_id' => $item['laundry_type_id'],
                'quantity' => $item['quantity'],
                'price' => $price,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::beginTransaction();
        try {
            // 建立訂單
            $order = Order::create([
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'total_price' => $totalPrice,
                'notes' => $validated['notes'] ?? null,
            ]);

            // 建立訂單項目
            foreach ($itemsData as &$item) {
                $item['order_id'] = $order->id;
            }

            DB::table('order_items')->insert($itemsData);

            DB::commit();

            // 回傳時加載相關資料
            $order->load(['items.laundryType']);

            return response()->json([
                'message' => '訂單已創建',
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '訂單創建失敗',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 更新訂單狀態 (僅員工可用)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed',
        ]);

        $order->status = $validated['status'];

        if ($validated['status'] === 'completed') {
            $order->completed_at = now();
        }

        $order->save();

        return response()->json([
            'message' => '訂單狀態已更新',
            'order' => $order->load(['items.laundryType'])
        ]);
    }
}
