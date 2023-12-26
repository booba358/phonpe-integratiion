<?php

namespace SparkoutTech\Phonepe\App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PhonepeController extends Controller
{

    public function redirectPhonepe($data, $saltkey)
    {
        try {
            $encode = base64_encode(json_encode($data));
            $saltIndex = 1;
            $string = $encode . '/pg/v1/pay' . $saltkey;
            $sha256 = hash('sha256', $string);
            $finalXHeader = $sha256 . '###' . $saltIndex;

            $client = new Client();
            $response = $client->post('https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $finalXHeader,
                ],
                'json' => ['request' => $encode],
            ]);

            $rData = json_decode($response->getBody()->getContents(), true);
            $url = $rData['data']['instrumentResponse']['redirectInfo']['url'];
            return $url;
        } catch (Exception $e) {
            Log::error("Error", [$e->getMessage()]);
            return back()->with('error', 'something went Wrong');
        }
    }


    public function refundAmount($data, $saltkey)
    {
        try {
            $encode = base64_encode(json_encode($data));
            $saltIndex = 1;
            $string = $encode . '/pg/v1/refund' . $saltkey;
            $sha256 = hash('sha256', $string);
            $finalXHeader = $sha256 . '###' . $saltIndex;

            $client = new Client();
            $response = $client->post('https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/refund', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $finalXHeader,
                ],
                'json' => ['request' => $encode],
            ]);
            $rData = json_decode($response->getBody()->getContents(), true);

            $finalXHeader1 = hash('sha256', '/pg/v1/status/' . $rData['data']['merchantId'] . '/' . $rData['data']['merchantTransactionId'] . $saltkey) . '###' . $saltIndex;
            $status = $client->get('https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/status/' . $rData['data']['merchantId'] . '/' . $rData['data']['merchantTransactionId'], [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $finalXHeader1,
                    'X-MERCHANT-ID' => $rData['data']['merchantTransactionId'],
                    'accept' => 'application/json',
                ]
            ]);

            $rstatus = json_decode($status->getBody()->getContents(), true);
            return $rstatus;
        } catch (Exception $e) {
            Log::error("Error", [$e->getMessage()]);
            return back()->with('error', 'something went Wrong');
        }
    }
}
