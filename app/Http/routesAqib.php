<?php
/**
 * Created by PhpStorm.
 * User: aqib
 * Date: 5/10/16
 * Time: 1:02 PM
 */

use App\Profile;

Route::get('/auth/signature', ['middleware' => ['auth'], 'uses' => 'SignatureController@getSignature', 'as' => 'signature::get']);
Route::post('/auth/signature', ['middleware' => ['auth'], 'as' => 'signature::post', 'uses' => 'SignatureController@save']);
Route::get('/twitter/twitter-testing', function(){
    (new \App\Services\TweetCollection\TweetCollectionService())->getBatchTweetByUser();
});