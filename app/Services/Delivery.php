<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Delivery
{
    private $client;

    public function __construct($client = null)
    {
        $this->client = $client ?? new Client();
    }

    public function toRemoteApp($serverUrl, $data)
    {

    
        $res = $this->client->get($serverUrl, [
            'json' => $data,
         ]);

        return $res;

    }

    public function toSimotel($server_simotel, $authkey, $data, $appname)
    {

        $remoteApp = new RemoteApp();

        
        $res =  $this->client->post($server_simotel, [
            'headers' => [
                'X-APIKEY' => $authkey['x-apikey'][0],
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
         ]);
        
        $data= json_decode($res->getBody()->getContents());
        
        $og_id =  $data->data->originated_call_id;
      
        $remoteApp->storeOg($og_id, $appname);

        return $data;

    }
}
