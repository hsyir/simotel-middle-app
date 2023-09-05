<?php 

namespace App\Services;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;

class FakerMethod{

    public function createHttpClient($data){
        
        $res = json_encode($data);

        $mock =new MockHandler([
            new Response(200,[],$res),
        ]);

        $handlerstack = HandlerStack::create($mock);

        return new Client(['handler'=>$handlerstack]);
    }

    public function fromSimotelFakeResponse()
    {
        return [
          "success" => 1,
          "message" => null,
          "data" => [
                     "originated_call_id" => "orig.call.1693808308.694172"
                    ]
     ];
    }

    public function fromRemoteAppFakeResponse(){
        return [
            'success'=>true,
        ];
    }

    public function getFakeFromRemoteAppData(){
        return [
            'caller'=>'09332999173',
            'callee'=>'100',
            'context'=>'main_routing',
            'caller_id'=>'101',
            'trunk_name'=>'moshaver',
            'timeout'=>'30',
        ];
    }

    public function getFakeFromSimotelData(){
        return [
            "event_name"=>"Cdr",
            "starttime"=>"2023-09-02 14:05:15.343172",
            "endtime"=>"2023-09-02 14:05:16.343172",
            "src"=>"101",
            "dst"=>"failed",
            "type"=>"outgoing",
            "disposition"=>"NO ANSWER",
            "duration"=>1,
            "wait"=>1,
            "unique_id"=>"1693663514.44",
            "cuid"=>"1693663514.44",
            "outgoing_point"=>"moshaver",
            "originated_call_id"=>"orig.call.1693808308.694172",
        
        ];
    }
}