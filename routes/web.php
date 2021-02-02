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


$router->group(['prefix' => 'api/v1', 'middleware' => ['setLang']], function () use ($router) {
    $router->group(['prefix' =>  'emails'], function () use ($router) {
        $router->get('', 'EmailController@index');
        $router->post('', 'EmailController@store');
        $router->group(['prefix' =>  '{uid}'], function ($uid) use ($router) {
            $router->get('', 'EmailController@show');
            $router->put('', 'EmailController@update');
            $router->delete('', 'EmailController@destroy');
        });
    });
    $router->group(['prefix' =>  'groups'], function () use ($router) {
        $router->get('', 'GroupController@index');
        $router->post('', 'GroupController@store');
        $router->group(['prefix' =>  '{uid}'], function ($uid) use ($router) {
            $router->get('', 'GroupController@show');
            $router->put('', 'GroupController@update');
            $router->delete('', 'GroupController@destroy');
            $router->group(['prefix' =>  'members'], function ($uid) use ($router) {
                $router->get('', 'GroupMemberController@index');
                $router->post('', 'GroupMemberController@store');
                $router->delete('{member_uid}', 'GroupMemberController@destroy');
            });
        });
    });
    $router->group(['prefix' =>  'templates'], function () use ($router) {
        $router->get('', 'TemplateController@index');
        $router->post('', 'TemplateController@store');
        $router->group(['prefix' =>  '{uid}'], function ($uid) use ($router) {
            $router->get('', 'TemplateController@show');
            $router->put('', 'TemplateController@update');
            $router->delete('', 'TemplateController@destroy');
            $router->group(['prefix' =>  'variables'], function ($uid) use ($router) {
                $router->get('', 'TemplateVariableController@index');
                $router->post('', 'TemplateVariableController@store');
                $router->delete('{variable_uid}', 'TemplateVariableController@destroy');
            });
        });
    });
    $router->group(['prefix' =>  'mailers'], function () use ($router) {
        $router->get('', 'MailerController@index');
        $router->post('', 'MailerController@store');
        $router->group(['prefix' =>  '{uid}'], function ($uid) use ($router) {
            $router->get('', 'MailerController@show');
            $router->put('', 'MailerController@update');
            $router->delete('', 'MailerController@destroy');
        });
    });
    $router->group(['prefix' =>  'destinyTypes'], function () use ($router) {
        $router->get('', 'DestinyTypeController@index');
    });
    $router->group(['prefix' =>  'sendTypes'], function () use ($router) {
        $router->get('', 'SendTypeController@index');
    });
    $router->group(['prefix' =>  'variables'], function () use ($router) {
        $router->get('', 'VariablesController@index');
    });
    $router->group(['prefix' =>  'expressMails'], function () use ($router) {
        $router->get('', 'ExpressMailController@index');
        $router->post('', 'ExpressMailController@store');
        $router->post('send', 'ExpressMailController@storeAndSend');
        $router->group(['prefix' =>  '{uid}'], function ($uid) use ($router) {
            $router->get('', 'ExpressMailController@show');
            $router->put('', 'ExpressMailController@update');
            $router->delete('', 'ExpressMailController@destroy');
            $router->post('send', 'ExpressMailController@send');
            $router->post('suspend', 'ExpressMailController@suspend');
            $router->group(['prefix' =>  'notSendFor'], function ($uid) use ($router) {
                $router->get('', 'ExpressMailNotSendForController@index');
                $router->post('', 'ExpressMailNotSendForController@store');
                $router->delete('{not_send_for_uid}', 'ExpressMailNotSendForController@destroy');
            });
        });
    });
    $router->group(['prefix' =>  'sends'], function () use ($router) {
        $router->get('', 'SendController@index');
        $router->post('', 'SendController@store');
        $router->post('send', 'SendController@storeAndSend');
        $router->group(['prefix' =>  '{uid}'], function ($uid) use ($router) {
            $router->get('', 'SendController@show');
            $router->put('', 'SendController@update');
            $router->delete('', 'SendController@destroy');
        });
    });
});
