<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/13/15
 * Time: 12:24 PM
 */
Route::group(['namespace' => 'Places', 'as' => 'places::'], function(){
    Route::get('/', [
        'as'=> 'home',
        'uses' => 'PlacesController@index'
    ]);
    Route::get('/{id}/show',[
        'as' => 'show',
        'uses' => 'PlacesController@show'
    ]);
    Route::get('/create',[
        'as' => 'create',
        'uses' => 'PlacesController@getCreate'
    ]);
    Route::post('/create',[
        'as' => 'create',
        'uses' => 'PlacesController@postCreate'
    ]);
    Route::get('/{id}/edit',[
        'as' => 'edit',
        'uses' => 'PlacesController@getEdit'
    ]);
    Route::post('/{id}/edit',[
        'as' => 'edit',
        'uses' => 'PlacesController@postEdit'
    ]);
    Route::delete('/{id}',[
        'as' => 'delete',
        'uses' => 'PlacesController@delete'
    ]);
});
