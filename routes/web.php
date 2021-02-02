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


$router->group(['prefix' => 'api/v1/{locale}', 'middleware' => ['setLang']], function () use ($router) {
    $router->group(['prefix' =>  'emails'], function () use ($router) {
        $router->get('', 'EmailController@index');
        $router->post('', 'EmailController@store');
        $router->get('{uid}', 'EmailController@show');
        $router->put('{uid}', 'EmailController@update');
        $router->delete('{uid}', 'EmailController@destroy');
    });
    $router->group(['prefix' =>  'groups'], function () use ($router) {
        $router->group(['prefix' =>  'members'], function () use ($router) {
            $router->get('{group_uid}', 'GroupMemberController@index');
            $router->post('{group_uid}', 'GroupMemberController@store');
            $router->delete('{group_uid}', 'GroupMemberController@destroy');
        });
        $router->get('', 'GroupController@index');
        $router->post('', 'GroupController@store');
        $router->get('{uid}', 'GroupController@show');
        $router->put('{uid}', 'GroupController@update');
        $router->delete('{uid}', 'GroupController@destroy');
    });
    $router->group(['prefix' =>  'templates'], function () use ($router) {
        $router->group(['prefix' =>  'variables'], function () use ($router) {
            $router->get('{template_uid}', 'TemplateVariableController@index');
            $router->post('{template_uid}', 'TemplateVariableController@store');
            $router->delete('{template_uid}', 'TemplateVariableController@destroy');
        });
        $router->get('', 'TemplateController@index');
        $router->post('', 'TemplateController@store');
        $router->get('{uid}', 'TemplateController@show');
        $router->put('{uid}', 'TemplateController@update');
        $router->delete('{uid}', 'TemplateController@destroy');
    });
    $router->group(['prefix' =>  'mailers'], function () use ($router) {
        $router->get('', 'MailerController@index');
        $router->post('', 'MailerController@store');
        $router->get('{uid}', 'MailerController@show');
        $router->put('{uid}', 'MailerController@update');
        $router->delete('{uid}', 'MailerController@destroy');
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
        $router->group(['prefix' =>  'notSendFor'], function () use ($router) {
            $router->get('{express_mail_uid}', 'ExpressMailController@index');
            $router->post('{express_mail_uid}', 'ExpressMailController@store');
            $router->delete('{express_mail_uid}', 'ExpressMailController@destroy');
        });
        $router->get('', 'ExpressMailController@index');
        $router->post('', 'ExpressMailController@store');
        $router->get('{uid}', 'ExpressMailController@show');
        $router->post('{uid}/send', 'ExpressMailController@send');
        $router->post('{uid}/suspend', 'ExpressMailController@suspend');
        $router->put('{uid}', 'ExpressMailController@update');
        $router->delete('{uid}', 'ExpressMailController@destroy');
    });
    $router->group(['prefix' =>  'sends'], function () use ($router) {
        $router->get('', 'SendController@index');
        $router->post('', 'SendController@store');
        $router->get('{uid}', 'SendController@show');
        $router->put('{uid}', 'SendController@update');
        $router->delete('{uid}', 'SendController@destroy');
    });
});
