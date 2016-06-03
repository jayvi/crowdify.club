<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/6/15
 * Time: 2:44 PM
 */

// Authentication routes...

Route::group([ 'as'=>'event::'],function(){
    Route::get('/',['as'=>'home','uses'=>'Event\EventController@index']);
    Route::post('/',['as'=> 'home','uses'=>'Event\EventController@index']);

    Route::get('/create',['as'=>'create', 'uses'=>  'Event\EventController@getCreate']);
    Route::post('/create', ['as'=>'create', 'uses'=> 'Event\EventController@postCreate']);
    Route::post('/upload',['as'=>'upload', 'uses'=>  'Event\EventController@upload']);
    Route::get('/my-events',['as'=>'my-events', 'uses'=>  'Event\EventController@myEvents']);
    Route::get('/my-events/{id}/edit',['as'=>'edit', 'uses'=>  'Event\EventController@getEdit']);
    Route::put('/my-events/{id}/edit',['as'=>'edit', 'uses'=>  'Event\EventController@postEdit']);
    Route::delete('/my-events/delete',['as'=>'delete', 'uses'=>  'Event\EventController@delete']);
    Route::get('/{id}/show',['as'=>'show', 'uses'=>  'Event\EventController@show']);
    Route::post('/{id}/register',['as'=>'register', 'uses'=>  'Event\EventController@register']);
    Route::post('/{id}/publish',['as'=>'publish', 'uses'=>  'Event\EventController@publishEvent']);
    Route::post('/{id}/un-publish',['as'=>'un-publish', 'uses'=>  'Event\EventController@unPublishEvent']);

    //ajax
    Route::post('/my-events/search',['as'=>'my-event-search', 'uses'=>  'Event\EventController@myEventSearch']);

    Route::post('{event_id}/share/{provider}',['as'=>'share', 'uses'=>  'Event\SocialShareController@share']);

    // tickets
    Route::post('/event/{event_id}/tickets', ['as' => 'tickets::create', 'uses' => 'Event\EventController@createTicket']);
    Route::delete('/event/{event_id}/tickets/{ticket_id}', ['as' => 'tickets::delete', 'uses' => 'Event\EventController@deleteTicket']);
    Route::post('/event/{event_id}/tickets/{ticket_id}/buy', ['as' => 'tickets::buy', 'uses' => 'Event\EventController@buyTicket']);
    Route::get('/event/{event_id}/tickets/{ticket_id}/payment', ['as' => 'tickets::payment-status', 'uses' => 'Event\EventController@getPaymentStatus']);

});




