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

class ReportsController extends Controller
{
    public function all_orders()
    {
        $booked =  Order::with('user')->get();
        return  $booked;
    }

    public function all_payments()
    {
        $payments = OrderPayment::with('order')->latest()->get();
        return response()->json(['data'=> $payments], 200);
    }

}
