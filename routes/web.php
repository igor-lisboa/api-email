<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->group(['prefix' =>  'emails'], function () use ($router) {
        $router->get('', 'EmailController@index');
        $router->post('', 'EmailController@store');
        $router->get('search', 'EmailController@search');
        $router->get('{uid}', 'EmailController@show');
        $router->put('{uid}', 'EmailController@update');
        $router->delete('{uid}', 'EmailController@destroy');
    });
});
