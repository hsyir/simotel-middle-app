<?php


use App\Http\Controllers\PoxyController;

use Illuminate\Support\Facades\Cache;
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/



$router->get("/",function(){

dd(request()->all());
});


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix'=>'proxy'],function () use ($router){
    $router->post('fromsimotel/Cdr','ProxyController@fromSimotel');
});

$router->group(['prefix'=>'proxy/fromremoteapp'],function () use ($router){
$router->post('[{path:.*}]','ProxyController@fromRemoteApp');
});