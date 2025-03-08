<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'notes',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    // 訂單狀態常量
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 獲取狀態標籤
    public function getStatusLabelAttribute()
    {
        return [
            self::STATUS_PENDING => '待處理',
            self::STATUS_PROCESSING => '處理中',
            self::STATUS_COMPLETED => '已完成',
            self::STATUS_CANCELLED => '已取消',
        ][$this->status] ?? $this->status;
    }
}
