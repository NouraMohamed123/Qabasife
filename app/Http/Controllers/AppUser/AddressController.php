<?php

namespace App\Http\Controllers\AppUser;

use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses;
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
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

        auth()->user()->addresses()->create($request->all());

        return redirect()->route('addresses.index')->with('success', 'Address created successfully.');
    }

    public function show(Address $address)
    {
        return view('addresses.show', compact('address'));
    }

    public function edit(Address $address)
    {
        return view('addresses.edit', compact('address'));
    }

    public function update(Request $request, Address $address)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $address->update($request->all());

        return redirect()->route('addresses.index')->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Address deleted successfully.');
    }
}
