<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'RegisterController@register');

Route::group(['prefix' => 'topics'], function () {
    Route::get('/', 'TopicController@index');
    Route::get('/{topic}', 'TopicController@show');
    Route::patch('/{topic}', 'TopicController@update')->middleware('auth:api');
    Route::delete('/{topic}', 'TopicController@destroy')->middleware('auth:api');
    Route::post('/', 'TopicController@store')->middleware('auth:api');

    Route::group(['prefix' => '/{topic}/posts'], function () {
        
        Route::post('/', 'PostController@store')->middleware('auth:api');
    });
});
