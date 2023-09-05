<?php 

namespace Tests\Feature;

use App\Services\FakerMethod;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Tests\TestCase;

class BasicTest extends TestCase{

    public function testRoute(){
        $faker = new FakerMethod();

        $client = $faker->createHttpClient($faker->fromSimotelFakeResponse());
        $res = $client->post('http://127.0.0.1:8000/proxy/fromremoteapp/api/v4/call/originate/act',[
           'headers'=>[
             'app'=>'02191305906',
             'X-APIKEY'=> 'LWDINkzNNYbA3LRUzsxeoF5WODUjRGqMmntfqq8BHB6NmRwq7g',
             'Content-Type' => 'application/json',
           ],
           'json'=>json_encode($faker->getFakeFromRemoteAppData())
         ]);
         
         $res_array = json_decode($res->getBody(), true);
         $this->assertArrayHasKey('success',$res_array);
         $this->assertArrayHasKey('message',$res_array);
         $this->assertArrayHasKey('data',$res_array);


         
        $client = $faker->createHttpClient($faker->fromRemoteAppFakeResponse());
        $res = $client->post('http://127.0.0.1:8000/proxy/fromsimotel', [
           'json' => json_encode($faker->getFakeFromSimotelData())
         ]);

        $res_array = json_decode($res->getBody(), true);
        $this->assertArrayHasKey('success', $res_array);
    }
}