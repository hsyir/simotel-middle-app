<?php

namespace App\Http\Controllers;

use App\Services\Delivery;
use App\Services\FakerMethod;
use App\Services\RemoteApp;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class ProxyController extends Controller
{
    public function fromSimotel(Request $request)
    {
        $remoteApp = new RemoteApp();
        try {

            //تشخیض سرور مورد نظر برای ارسال داده ها و cdr از طریق og
            $remoteApp->initWithOg($request->get('originated_call_id'));

            $delivery = new Delivery();
            
            //ارسال داده ها به سمت سرور تشخیض داده شده
            $res =  $delivery->toRemoteApp($remoteApp->getUrl(), $request->all());
            
            return response()->json([
                $res
            ]);

        } catch(ClientException $ex) {
            $response = $ex->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();

            return response()->json([
                "success" => false,
                "error_message" => $ex->getMessage(),
                "response_body" => $responseBodyAsString,
            ], $response->getStatusCode());
        }

    }

    public function fromRemoteApp(Request $request)
    {

        $app = new RemoteApp();

        try {
            $header = $request->header();
            
            //تشخیض از طریق app-name ارسالی از سمت سرور درخواست دهنده تماس
            $app->initWithName($header['app'][0]);

            $simotel_url = $app->getSimotelUrl();


            
            $split_url = explode('proxy/fromremoteapp/', $request->url());
            $server_simotel = $simotel_url.$split_url[1];

            $delivery = new Delivery();

            //تحویل به سیموتل مقصد 
            $response = $delivery->toSimotel($server_simotel, $header, $request->all(), $app->getName());
            
            return response()->json([
              $response
            ]) ;

        } catch(ClientException $ex) {
            $response = $ex->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();

            dd( response()->json([
                "success" => false,
                "error_message" => $ex->getMessage(),
                "response_body" => $responseBodyAsString,
            ], $response->getStatusCode()));
        }
    }

    
}
