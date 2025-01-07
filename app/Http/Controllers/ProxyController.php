<?php
namespace App\Http\Controllers;

use App\Services\Delivery;
use App\Services\FakerMethod;
use App\Services\RemoteApp;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\ClientException;
use Log;

class ProxyController extends Controller
{
    public function fromSimotel(Request $request)
    {


        $remoteApp = new RemoteApp();
        try {

           $url =  $remoteApp->initWithOgUrl($request->originated_call_id);

            //Log::info($remoteApp->getUrl());

            $delivery = new Delivery();


            //ارسال داده ها به سمت سرور تشخیض داده شده
            $res = $delivery->toRemoteApp($url, $request->all());

            return response()->json([
                "res"=>$res
            ]);

        } catch(ClientException $ex) {
            $response = $ex->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();

            return response()->json([
                "success" => false,
                "error_message" => $ex->getMessage(),
                "response_body" => $responseBodyAsString,
            ], $response->getStatusCode());
        } catch(\Exception $ex) {

            return response()->json([
                "success" => false,
                "error_message" => $ex->getMessage(),
            ], 404);
        }

    }

    public function fromRemoteApp(Request $request)
    {


        try {
            $header = $request->header();
            //تشخیض از طریق app-name ارسالی از سمت سرور درخواست دهنده تماس
            //$app->initWithName($header['app'][0]);


            // dd($header);
            $app_url = $header['x-tele-url'][0];
            $simotel_url = $header['x-simotel-url'][0];
            $split_url = explode('proxy/fromremoteapp/', $request->url());
            $server_simotel = $simotel_url . $split_url[1];

            $delivery = new Delivery();
            //تحویل به سیموتل مقصد
            $response = $delivery->toSimotel($server_simotel, $header, $request->all(), $app_url );

            return $response;

        } catch(ClientException $ex) {
            $response = $ex->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            Log::info("errrrrrrrrrrr:".$response);
            dd(response()->json([
                "success" => false,
                "error_message" => $ex->getMessage(),
                "response_body" => $responseBodyAsString,
            ], $response->getStatusCode()));
        }
    }


}