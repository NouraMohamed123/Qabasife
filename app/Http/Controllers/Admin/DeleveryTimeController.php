<?php

namespace App\Http\Controllers\Admin;

use App\Models\DeleveryTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeleveryTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deleveryTimes = DeleveryTime::all();
        return response()->json($deleveryTimes);
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
        $validatedData = $request->validate([
            'time' => 'required',
        ]);

        $deleveryTime = DeleveryTime::create($validatedData);
        return response()->json($deleveryTime, 201);
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
    public function update(Request $request,DeleveryTime $deleveryTime)
    {
        $validatedData = $request->validate([
            'time' => 'required',
        ]);

        $deleveryTime->update($validatedData);
        return response()->json($deleveryTime);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleveryTime $deleveryTime)
    {
        $deleveryTime->delete();
        return response()->json(null, 204);
    }
}
