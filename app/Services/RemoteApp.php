<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RemoteApp {

    private $name;
    private $url;
    private $simotel_url;
    

    public function initWithName($name){
        
        $app = config('app.remote_apps.'.$name);
        
        $this->name = $name;
        $this->url=$app['url'];
        // $this->url=$name;
        $this->simotel_url = $app['simotel_url'];

      
        
    }

    public function getName(){
        return $this->name;
    }

    public function getUrl(){
        return $this->url;
    }

    public function getSimotelUrl(){
        return $this->simotel_url;
    }

    public function storeOg($og,$appname){
        // dd($og,$appname);
        Cache::put($og,$appname,500);
    }

   public function  initWithOg($og){
        $name = Cache::get($og,''); 
        
        $this->initWithName($name);
   }


}