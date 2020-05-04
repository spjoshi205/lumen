<?php

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



$app->get('/', function () use ($app) {
    //return $app->welcome();
});

$app->get('/pass', function () use ($app) {
    return app('hash')->make('Admin@123');;
});

// define because if i use group have to use full name space name
$prefix = "api/";
// change old product status
$app->get('changestatus', 'OrdersController@changestatus');
// find all product
$app->get($prefix.'users/{id}', 'AuthController@login');
// find all product
$app->get($prefix.'products', ['middleware' => 'Token','uses'=>'ProductsController@fetchproducts']);
// add/edit product data
$app->post($prefix.'product', ['middleware' => 'Token','uses'=>'ProductsController@add']);
// delete product
$app->post($prefix.'delete-product', ['middleware' => 'Token','uses'=>'ProductsController@delete']);
// add order
$app->post($prefix.'order', ['middleware' => 'Token','uses'=>'OrdersController@add']);
// find all product
$app->get($prefix.'get-orders/{orderstatus}', ['middleware' => 'Token','uses'=>'OrdersController@filter']);
// find all product
$app->get($prefix.'accept-order/{id}', ['middleware' => 'Token','uses'=>'OrdersController@accept']);
