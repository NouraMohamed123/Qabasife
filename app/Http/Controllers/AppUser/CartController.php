<?php

namespace App\Http\Controllers\AppUser;

use App\Models\Cart;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function addItemToCart(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'product_id' => 'required|exists:products,id',
            'count' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }
        $user = Auth::guard('app_users')->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $user_id = Auth::guard('app_users')->user()->id;


        $cart = Cart::where('user_id', $user_id)
            ->where('product_id', $request->product_id)
            ->first();

            if ($cart) {

            $cart->count = $request->count != 0 ? $request->count : $cart->count + 1;
            $cart->save();
        } else {

            $cart = Cart::create([
                'user_id' => $user_id,
                'product_id' => $request->product_id,
                'count' => $request->count == 0 ? 1 : $request->count,
            ]);
        }
        return response()->json([
            'status' => true,
            'count' => intval($cart->count),
            'message' => 'Item added to cart successfully',
        ], 200);
    }
    public function removeItemFromCart(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'product_id' => 'required|exists:products,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 422);
        }
        $user = Auth::guard('app_users')->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $user_id = Auth::guard('app_users')->user()->id;

        $cart = Cart::where('user_id', $user_id)->where('product_id', $request->product_id)->first();


            $cart->delete();
            return response()->json([
                'status' => true,
                'count' => 0,
                'message' => 'Item removed from cart successfully',
            ], 200);

    }

    public function getCartItems(Request $request)
    {

        $user = Auth::guard('app_users')->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $user_id = Auth::guard('app_users')->user()->id;
        $subtotal = 0.0;
        $deliveryFees = 0.0;

        $carts = Cart::where('user_id', $user_id)->get();

        $items = $carts->map(function ($cart) {
            $product = Product::where('id', $cart->product_id)->first();
            $cost = $cart->count *  $product->price;

            return [
                'id' => $product->id,
                'name' => $product->name ,
                'photo' => $product->photo,
                'count' => $cart->count,
                'total' => $cost,
            ];
        });

        $totalCost =  $items->sum('total') ?? 0.0;

        return response()->json([
            'status' => true,
            'totalCost' => $totalCost ?? 0.0,
            'items' => $items,
        ], 200);
    }

    public function getUserCart()
    {
        $user = Auth::guard('app_users')->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $user_id = Auth::guard('app_users')->user()->id;
        $count_cart = Cart::where('user_id', $user_id)
            ->count();
        return response()->json([
            'status' => true,
            'count' => $count_cart,
        ], 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
