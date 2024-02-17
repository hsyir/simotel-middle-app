<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class Delivery
{
    private $client;

    public function __construct($client = null)
    {
        $this->client = $client ?? new Client();
    }

    public function toRemoteApp($serverUrl, $data)
    {    



        //ارسال به سرور اپ مورد نطر
        $res = $this->client->get($serverUrl, [
            'json' => $data,
         ]);
        Log::info("remote: " . $res->getBody()->getContents());
        return $res;

    }

    public function toSimotel($server_simotel, $authkey, $data, $appname)
    {

        $remoteApp = new RemoteApp();
        
        // ارسال به سیموتل مقصد توسط apikey که در مرحله ثبلی از hgeader دریات شده بود
        $res =  $this->client->post($server_simotel, [
            'headers' => [
                'X-APIKEY' => $authkey['x-apikey'][0],
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
         ]);
        
       $data= json_decode($res->getBody()->getContents());
        
        //تشخیض og_id تماس
        $og_id =  $data->data->originated_call_id;
         

        //ذخیره og به وسیله اapp-name
       $remoteApp->storeOg($og_id, $appname);
         
        // return response()->json([
        //     $data
        // ]) ;
        return $res;

    }
}
