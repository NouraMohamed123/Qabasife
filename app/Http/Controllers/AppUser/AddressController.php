<?php

namespace App\Http\Controllers\AppUser;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {

        $user = Auth::guard('app_users')->user();
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        $addresses = $user->addresses;
        return response()->json(['isSuccess' => true,'data'=>  $addresses], 200);
    }



    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

         Auth::guard('app_users')->user()->addresses()->create($request->all());

        return redirect()->route('addresses.index')->with('success', 'Address created successfully.');
    }





    public function destroy(Address $address)
    {
        $address->delete();

        return response()->json(['isSuccess' => true], 200);
    }
}
