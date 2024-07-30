<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Booking;
use App\Models\AppUsers;
use App\Models\Membership;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use App\Models\SubscriptionPayment;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class ReportsController extends Controller
{
    public function all_orders()
    {
        $order =  Order::with('user','orderItems')->get();
        return response()->json(['data' =>new OrderResource( $order)], 200);

    }

    public function getOrderCount()
    {
        $count = Order::count();

        return response()->json([
            "successful" => true,
            "message" => "عملية العرض تمت بنجاح",
            'data' => $count
        ]);
    }

}
