<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::get('/', function () {
    return Redirect::to('/api-docs');
});


Route::group(['prefix' => 'api'], function () {
    post('register', 'TokenAuthController@register');
    post('authenticate', 'TokenAuthController@authenticate');
    get('authenticate/user', 'TokenAuthController@getAuthenticatedUser');

    Route::group(['middleware' => 'jwt.auth'], function () {
        post('logout', 'TokenAuthController@logout');
        resource('appointment_requests', 'AppointmentRequestController', ['except' => ['create', 'edit']]);
        patch('appointment_requests/{id}/confirm', 'AppointmentRequestController@confirm');
        patch('appointment_requests/{id}/cancel', 'AppointmentRequestController@cancel');
    });

});
