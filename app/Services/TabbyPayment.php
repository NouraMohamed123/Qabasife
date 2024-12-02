<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class TabbyPayment
{
    public function __construct()
    {
        Config::set('services.tabby.pk_test', 'pk_test_7f9a63b4-4c7e-4289-8ab3-df58fb021fe8');
        Config::set('services.tabby.sk_test', 'sk_test_e5734624-8b6c-466c-ba73-445447105365');
        Config::set('services.tabby.base_url', 'https://api.tabby.ai/api/v2/');
    }

    public function createSession($data)
    {
        $body = $this->getConfig($data);

        $http = Http::withToken(Config::get('services.tabby.pk_test'))
                    ->baseUrl(Config::get('services.tabby.base_url'))
                    ->withOptions([
                        'verify' => 'D:\\laragon\\etc\\ssl\\cacert.pem',
                    ]);

        $response = $http->post('checkout', $body);

        return $response->object();
    }

    public function getSession($payment_id)
    {
        $http = Http::withToken(Config::get('services.tabby.sk_test'))
                    ->baseUrl(Config::get('services.tabby.base_url'))
                    ->withOptions([
                        'verify' => 'D:\\laragon\\etc\\ssl\\cacert.pem',
                    ]);

        $url = 'payments/' . $payment_id;

        $response = $http->get($url);

        return $response->object();
    }

    public function getConfig($data)
    {
        return [
            "payment" => [
                "amount" => $data['amount'],
                "currency" => $data['currency'],
                "description" => $data['description'],
                "buyer" => [
                    "phone" => $data['buyer_phone'],
                    "email" => $data['buyer_email'],
                    "name" => $data['full_name'],
                    "dob" => "2019-08-24"
                ],
                "shipping_address" => [
                    "city" => $data['city'],
                    "address" => $data['address'],
                    "zip" => $data['zip'],
                ],
                "order" => [
                    "tax_amount" => "0.00",
                    "shipping_amount" => "0.00",
                    "discount_amount" => "0.00",
                    "updated_at" => "2019-08-24T14:15:22Z",
                    "reference_id" => $data['order_id'],
                    "items" => $data['items'],
                ],
                "buyer_history" => [
                    "registered_since" => $data['registered_since'],
                    "loyalty_level" => $data['loyalty_level'],
                ],
            ],
            "lang" => app()->getLocale(),
            "merchant_code" => "شركة التنظيف الاعمقsau",
            "merchant_urls" => [
                "success" => $data['success-url'],
                "cancel" => $data['cancel-url'],
                "failure" => $data['failure-url'],
            ]
        ];
    }
}
