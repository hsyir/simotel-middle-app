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

         $res =  $this->client->post($serverUrl, [
                'headers'=>[
                'Accept' => 'application/json',
                ],
            'json' =>$data,
         ]);

        return $res->getBody()->getContents();

    }

    public function toSimotel($server_simotel, $authkey, $data, $simotel_url)
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
        //  dd($data);
        //تشخیض og_id تماس
        $og_id =  $data->data->originated_call_id;


        //ذخیره og به وسیله اapp-name
       $remoteApp->storeOg($og_id, $simotel_url);

        return $res;

    }
}