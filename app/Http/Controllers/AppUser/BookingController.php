<?php

namespace App\Http\Controllers\AppUser;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Service;
use App\Events\BookedEvent;
use Illuminate\Http\Request;
use App\Models\ControlBooking;
use App\Services\TabbyPayment;
use App\Services\paylinkPayment;
use App\Services\TammaraPayment;
use App\Services\WatsapIntegration;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AppUserBooking;
use Illuminate\Support\Facades\Validator;
use App\Notifications\BookingNotification;
use Illuminate\Support\Facades\Notification;
use League\CommonMark\Extension\TableOfContents\TableOfContentsBuilder;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $paylink;
    public $tabby;
    public $tammara;
    public function __construct()
    {
        $this->paylink = new paylinkPayment();
        $this->tabby = new TabbyPayment();
        $this->tammara = new TammaraPayment();
    }
    public function userBookings()
    {

        $user = Auth::guard('app_users')->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $orders = Order::where('app_users_id', $user->id)->get();
        return response()->json(['orders' => $orders], 200);
    }
    public function getServiceDetails($serviceId)
    {

        $service = Service::with('optionTypes.options')->find($serviceId);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }
        $serviceDetails = [
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'option_types' => []
        ];

        foreach ($service->optionTypes as $optionType) {
            $options = $optionType->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'value' => $option->value,
                ];
            });

            $serviceDetails['option_types'][] = [
                'id' => $optionType->id,
                'name' => $optionType->name,
                'options' => $options,
            ];
        }

        return response()->json($serviceDetails);
    }



    public function bookMultipleServices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id'        => 'required|string|exists:addresses,id',
            'delevery_time_id'        => 'required',
            'total_price' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = Auth::guard('app_users')->user();
        if (!$user) {
            return response()->json(['error' => 'Please login first'], 401);
        }



        $carts = Cart::where('user_id', $user->id)->get();
        $items = [];

        if ($carts->isEmpty()) {
            return response()->json([
                'error' => 'cart is empty'
            ], 422);
        }
        $order = Order::create([
            'app_users_id'     => $user->id,
            'total_price' => $request->total_price,
            'addresses_id'     => $request->address_id,
            'delevery_time_id'        => $request->delevery_time_id,
            'payment_method'=>'cash'

        ]);
        foreach ($carts as $cart) {
            $product = Product::where('id', $cart->product_id)->first();
            $cost = $cart->count * $product->price;
            $booking = OrderItem::create([

                'order_id' => $order->id,
                'product_id'  => $product->id,
                'quantity' => $product->count,
                'price' => $cost,


            ]);
        }



        return response()->json(['message' => 'عملية الحجز تمت بنجاح'], 201);
    }


    public function show(string $id)
    {
        $booking = Booking::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json(['booking' => $booking], 200);
    }



    public function cancelBooking($id)
    {

        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }
        $user = Auth::guard('app_users')->user();
        if (!$user || $booking->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $booking->delete();
        return response()->json(['message' => 'Booking canceled successfully'], 200);
    }
    public function checkCoupon(Request $request)
    {
        return checkCoupon($request->couponCode, $request->totalAmount);
    }
    public function sucess(Request $request)
    {
        return   $this->tabby->calbackPayment($request);
    }
    public function cancel(Request $request)
    {
        return response()->json(["error" => 'error', 'Data' => 'payment canceld'], 404);
    }
    public function failure(Request $request)
    {
        return response()->json(["error" => 'error', 'Data' => 'payment failure'], 404);
    }
    public function paylinkResult(Request $request)
    {

        return   $this->paylink->calbackPayment($request);
    }
}
