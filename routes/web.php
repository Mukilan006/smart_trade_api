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
    return "Api is live";
});

$router->group(['prefix' => 'api', 'middleware' => 'apikey'], function () use ($router) {
    $router->post('/user/register', 'UserController@register');
    $router->post('/auth/login', 'AuthController@login');
    $router->post('/auth/logout', ['middleware' => 'token', 'use' => 'AuthController@logout']);
    $router->post('/auth/refresh', 'AuthController@refresh');
    $router->get('/common/wasabi_file', 'Admin\CommonController@getWasabiFile');
    $router->get('/common/wasabi_video', 'Admin\CommonController@getWasabiVideo');
    $router->get('/get_video_url', ['middleware' => 'token', 'use' => 'Admin\CommonController@getVideoUrl']);
    $router->group(['prefix' => 'admin'], function () use ($router) {
        $router->post('/common/image_upload', 'Admin\CommonController@imageUpload');
        $router->post('/common/file_upload', 'Admin\CommonController@fileUpload');
        $router->post('/add_banner', 'Admin\HomePageController@addBanner');
        $router->post('/add_demo', 'Admin\HomePageController@addDemoVideo');
        $router->get('/initial_data', 'Admin\HomePageController@initialData');
    });
});
