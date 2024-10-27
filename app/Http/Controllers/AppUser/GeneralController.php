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
use App\Models\DeleveryTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\ProductResource;
use App\Models\AppUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
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

    public function  getDeleveryTimes(){
        $deleveryTimes = DeleveryTime::all();
        return response()->json($deleveryTimes);
    }

    public function check_number(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "false", 'error' => $validator->errors()], 422);
        }

        //check if the phone is exists
        $phone = "009665" . $request->phone;
        $user = AppUsers::where('phone', $phone)->first();

        $is_new_user = true;

        //generate OTP
         $otp = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        try {
            if ($user) {
                //save code in database
                $user->otp = $otp;
                $user->save();
                if ($user->name != "new_user") {
                    $is_new_user = false;
                }

                if ($user->status == 0) {
                    return response()->json(['success' => "false", 'is_new' => false], 403);
                }
            } else {
                //create user
                $user = AppUsers::create([
                    'name' => 'new_user',
                    'phone' => $phone,
                    'otp' => $otp,
                    'api_token' => Str::random(100),
                ]);
            }

            $text = "رمز التحقق هو: " . $otp . " للاستخدام في تطبيق قبس الحياة  ";
            $this->send_sms($phone, $text);

            return response()->json(['success' => "true", 'is_new' => $is_new_user], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => "false", 'is_new' => false], 403);
        }

    }
    public function check_opt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => ['required'],
            'otp' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => "false", 'error' => $validator->errors()], 422);
        }

        $phone = "009665". $request->phone;
        // if($request->phone == "93783093")
        // {
        //     $user = AppUsers::where('phone', $phone)->first();
        //     $token = JWTAuth::fromUser($user);

        //     return response()->json([
        //         'access_token' => $token,
        //         "data" => $user,
        //         'expires_in' => JWTAuth::factory()->getTTL() * 60,
        //     ]);
        // }

        $user = AppUsers::where('phone', $phone)->where('otp',$request->otp)->first();
        if($user)
        {
            $user->delete();
            return response()->json([
              
                'message' =>'deleted successfuly',
            ]);
        }else{
            return response()->json([ 'error' => 'wrong data'], 403);
        }
    }
    public function send_sms($number, $text)
    {
        try {

            $token = "1c9b95ac634c51d4e12d92e6e5bb2cd5";
            $url = "https://api.taqnyat.sa/v1/messages";

            $sender = "gabas";

            //You may send message to 1 destination or multiple destinations by supply destinations number in one string and separate the numbers with "," or provide a array of strings
            //يمكنك ارسال الرسائل الى جهة واحدة من خلال او اكثر تزويدنا بالارقام في متغير نصي واحد تكون فيه الارقام مفصولة عن بعضها باستخدام "," او من خلال تزويدنا بمصفوفة من الارقام
            $recipients = $number;

            $body = $text;

            $customRequest = "POST"; //POST or GET
            $data = array(
                'bearerTokens' => $token,
                'sender' => $sender,
                'recipients' => $recipients,
                'body' => $body,
            );
            //  Log::info('SMS Response', ['number' => $number]);

            $data = json_encode($data);

            $curl = curl_init();


            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $customRequest,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array('Content-Type:application/json'),
            ));


            $response = curl_exec($curl);

        // Log the response
        //  Log::info('SMS Response', ['response' => $response]);

        if ($response === false) {
            $error = curl_error($curl);
            // Log curl error
            //  Log::error('Curl error', ['error' => $error]);
            curl_close($curl);
            return false;
        }

        curl_close($curl);
        return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
