<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 9/17/15
 * Time: 12:44 PM
 */

Route::group(['namespace' => 'Blog', 'as' => 'blog::'], function(){
    Route::get('/',['as'=>'home','uses'=>'BlogPostController@index']);
    Route::post('/',['as'=>'home','uses'=>'BlogPostController@index']);
    Route::get('/create',['as'=>'create','uses'=>'BlogPostController@getCreate']);
    Route::post('/create',['as'=>'create','uses'=>'BlogPostController@postCreate']);
    Route::get('/my-blogs',['as'=>'my-blogs', 'uses'=>  'BlogPostController@myBlogs']);
    Route::get('/{id}/show',['as'=>'show', 'uses'=>  'BlogPostController@show']);
    Route::get('/{id}/edit',['as'=>'edit', 'uses'=>  'BlogPostController@getEdit']);
    Route::put('/{id}/edit',['as'=>'edit', 'uses'=>  'BlogPostController@postEdit']);
    Route::delete('/delete',['as'=>'delete', 'uses'=>  'BlogPostController@delete']);
    Route::get('/{id}/publish',['as'=>'publish', 'uses'=>  'BlogPostController@publish']);
    Route::get('/{id}/un_publish',['as'=>'un-publish', 'uses'=>  'BlogPostController@unPublish']);
});

