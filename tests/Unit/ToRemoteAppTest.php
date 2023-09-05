<?php

namespace Tests\Unit;

use App\Services\RemoteApp;
use App\Services\Delivery;
use App\Services\FakerMethod;
use GuzzleHttp\Exception\ClientException;
use Tests\TestCase;

class ToRemoteAppTest extends TestCase
{
    public function testDeliver()
    {
        $remoteApp = new RemoteApp();
        $faker = new FakerMethod();
        try {

            $remoteApp->initWithOg('orig.call.1693808308.694172');

            $delivery = new Delivery($faker->createHttpClient($faker->fromRemoteAppFakeResponse()));


            $res =  $delivery->toRemoteApp($remoteApp->getUrl(), $faker->getFakeFromSimotelData());

            $res_array = json_decode($res->getBody(), true);
            $this->assertArrayHasKey('success', $res_array);

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
