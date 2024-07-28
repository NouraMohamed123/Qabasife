<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products=Product::paginate($request->get('per_page', 50));

        return response()->json(['data' => ProductResource::collection($products)], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'required|image|mimes:jpeg,webp,png,jpg,gif,pdf|max:2048',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }
        if ($request->file('photo')) {
            $avatar = $request->file('photo');
            $avatar->store('uploads/products/', 'public');
            $photo = $avatar->hashName();
        } else {
            $photo = null;
        }
        $product=Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'offer_price' => $request->offer_price,
            'photo' => $photo,

        ]);
        return (new ProductResource($product ))
        ->response()
        ->setStatusCode(200);
        return response()->json(['data' => new ProductResource($product )], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {

        return response()->json(['data' => new ProductResource($product )], 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,webp,png,jpg,gif,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ], 400);
        }

        // Check if a new photo is provided
        if ($request->hasFile('photo')) {
            // Delete the existing photo if it exists
            if ($product->photo) {
                Storage::delete('uploads/products/' . $product->photo);
            }
            // Store the new photo
            $avatar = $request->file('photo');
            $avatar->store('uploads/products/', 'public');
            $photo = $avatar->hashName();
        } else {
            $photo = $product->photo; // Retain the existing photo
        }

        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'offer_price' => $request->offer_price,
            'photo' => $photo,

        ]);


        return response()->json(['data' => new ProductResource($product )], 200);

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if the Product exists
        if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
        }
        if ($product->photo) {
            // Assuming 'personal_photo' is the attribute storing the file name
            $photoPath = 'uploads/products/' . $product->photo;

            // Delete photo from storage
            Storage::delete($photoPath);
        }

        // Delete the user
        $product->delete();

        return response()->json(['message' => 'العملية تمت بنجاح']);

    }
    public function getProductCount()
    {
        $count = Product::count();

        return response()->json([
            "successful" => true,
            "message" => "عملية العرض تمت بنجاح",
            'data' => $count
        ]);
    }
}
