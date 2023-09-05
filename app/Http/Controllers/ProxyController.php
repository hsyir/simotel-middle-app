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

            $remoteApp->initWithOg($request->get('originated_call_id'));

            $delivery = new Delivery();
            
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
            
            $app->initWithName($header['app'][0]);

            $simotel_url = $app->getSimotelUrl();

            $split_url = explode('proxy/fromremoteapp/', $request->url());
            $server_simotel = $simotel_url.$split_url[1];

            $delivery = new Delivery();

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
