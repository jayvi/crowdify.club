<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 10/23/15
 * Time: 10:12 AM
 */

/*
 * common routes for all subdomain
 * */
Route::post('image/upload', [
    'as' => 'image::upload',
    'uses' => 'FileUploadController@uploadImage'
]);
Route::any('/site/search',[
    'as' => 'site::search',
    'uses' => 'Perk\WelcomeController@search'
]);