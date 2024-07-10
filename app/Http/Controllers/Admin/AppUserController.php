<?php

namespace App\Http\Controllers\Admin;

use App\Models\AppUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\AppUserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AppUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = AppUsers::paginate($request->get('per_page', 50));

        return AppUserResource::collection($users);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:250',
            'email' => 'required|unique:app_users',
            'password' => 'required|min:8|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "false", 'error' => $validator->errors()], 422);
        }
        if ($request->file('image')) {
            $avatar = $request->file('image');
            $avatar->store('uploads/users/', 'public');
            $image = $avatar->hashName();
        }
        $user = AppUsers::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'phone'=>$request->phone,
            'image'=> $image,
            'type_shipping_agent'=>1
        ]);

        $new_user = AppUsers::where('email', $request->email)->first();
        return response()->json(['success' => "true", 'user' => $new_user], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(AppUsers $user)
    {
        return response()->json([
            'successful' => true,
            'message' => 'Operation retrieved successfully',
            'data' => new AppUserResource($user)
        ], 200);
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
    public function update(Request $request,AppUsers $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:250',
            'email' => 'required|unique:app_users,email,' . $request->id,
            'password' => 'required|min:8|max:250',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "false", 'error' => $validator->errors()], 422);
        }
        if ($request->file('image')) {
            $avatar = $request->file('image');
            $avatar->store('uploads/users/', 'public');
            $image = $avatar->hashName();
        }else{
            $image = $user->image;
        }
        $user = $user->update([
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'phone'=>$request->phone,
            'image'=> $image,
            'type_shipping_agent'=>1
        ]);

        $new_user = AppUsers::where('email', $request->email)->first();
        return response()->json(['success' => "true", 'user' => $new_user], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppUsers $user)
    {
        if ($user->image) {
            $photoPath = 'uploads/user/' .$user->image;
            Storage::delete($photoPath);
        }
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
