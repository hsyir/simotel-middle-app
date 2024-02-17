<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\throwException;

class RemoteApp {

    private $name;
    private $url;
    private $simotel_url;
    

    public function initWithName($name){
        
        $app = config('app.remote_apps.'.$name);
        if(!$app){
            throw new \Exception("Originated Call Not Found in proxy-cache");
        }
        
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
        Cache::put($og,$appname,5000);
       
    }

   public function  initWithOg($og){
        $name = Cache::get($og,''); 
        if(!$name)
            throw new \Exception("og name not found ");
        
        $this->initWithName($name);
   }


}