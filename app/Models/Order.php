<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded =[];
    public function user()
    {
        return $this->belongsTo(AppUsers::class,'app_users_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
