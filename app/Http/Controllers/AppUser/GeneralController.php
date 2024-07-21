<?php

namespace App\Http\Controllers\AppUser;

use App\Models\Area;
use App\Models\City;
use App\Models\Term;
use App\Models\AboutUs;
use App\Models\Contact;
use App\Models\Privacy;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProductResource;

class GeneralController extends Controller
{
    public function getAllProducts()
    {
        $products = Product::all();
        return response()->json(['data' => $products], 200);
    }
    public function getProductMostCommon()
    {
        $products = Product::withCount('orderItems')
        ->orderBy('order_items_count', 'desc')
        ->get();
        $productResources = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'price' => $product->price,
                'offer_price' => $product->offer_price,
                'photo' => $product->photo,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        return response()->json(['data' => $productResources], 200);
    }
    public function getAllTerm()
    {
        $term = Term::all();
        return response()->json(['term' => $term], 200);
    }
    public function getAllprivacy()
    {
        $privacy = Privacy::all();
        return response()->json(['privacy' => $privacy], 200);
    }
    public function getAllsetting()
    {
        $settings = Setting::pluck('value', 'key')
        ->toArray();
        $image = asset('uploads/settings/' .  $settings['site_logo']);
        $settings['site_logo'] =    $image;
        return  $settings;
    }



    public function getContactUs()
    {
        $contactUs = Contact::all();
        return response()->json(['contact_us' => $contactUs], 200);
    }

    public function getAboutUs()
    {
        $aboutUs = AboutUs::all();
        return response()->json(['about_us' => $aboutUs], 200);
    }
    public function getQuestion()
    {
        $question = Question::all();
        return response()->json(['Question' => $question], 200);
    }




}
