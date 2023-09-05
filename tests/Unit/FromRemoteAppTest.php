<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use App\Services\RemoteApp;
use App\Services\Delivery;
use App\Services\FakerMethod;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class FromRemoteAppTest extends TestCase
{
  
    public function testSend()
    {
        $app = new RemoteApp();

        try {
            $header = [
              'app'=>array('02191305906'),
              'x-apikey'=>array('LWDINkzNNYbA3LRUzsxeoF5WODUjRGqMmntfqq8BHB6NmRwq7g'),
            ];
            $app->initWithName($header['app'][0]);
            $simotel_url = $app->getSimotelUrl();

            $split_url = explode('proxy/fromremoteapp/', 'http://127.0.0.1:8000/proxy/fromremoteapp/api/v4/call/originate/act');
            $server_simotel = $simotel_url.$split_url[1];

            $faker = new FakerMethod();
            $delivery = new Delivery($faker->createHttpClient($faker->fromSimotelFakeResponse()));
            $response = $delivery->toSimotel($server_simotel, $header, $faker->getFakeFromRemoteAppData(), $app->getName());
           
            $res_array =json_decode(json_encode($response),true);

            $this->assertArrayHasKey('success',$res_array);
            $this->assertArrayHasKey('message',$res_array);
            $this->assertArrayHasKey('data',$res_array);

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
}
