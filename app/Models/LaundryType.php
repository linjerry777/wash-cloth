<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryType extends Model
{
    /** @use HasFactory<\Database\Factories\LaundryTypeFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'description'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
