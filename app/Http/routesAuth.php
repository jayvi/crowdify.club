<?php


Route::group(['prefix'=>'auth','as'=>'auth::'], function(){

    Route::get('login',['as'=>'login', 'uses'=>  'Auth\AuthController@getlogin']);
    Route::post('login', ['as'=>'login', 'uses'=> 'Auth\AuthController@postLogin']);
    Route::get('logout', ['as'=>'logout', 'uses'=> 'Auth\AuthController@getLogout']);

    // Registration routes...
    Route::get('register', ['as'=>'register', 'uses'=> 'Auth\AuthController@getRegister']);
    Route::post('register', ['as'=>'register', 'uses'=> 'Auth\AuthController@postRegister']);

    //social login routes
    Route::get('{provider}/login',['as'=>'social::login', 'uses'=>'Auth\AuthController@socialLogin']);
    Route::get('{provider}/connect',['as'=>'social::connect', 'uses'=>'Auth\AuthController@socialConnect']);
    Route::get('{provider}/callback',['as'=>'social::callback', 'uses'=>'Auth\AuthController@socialLoginCallback']);
    Route::post('{provider}/disconnect',['as'=>'social::disconnect', 'uses'=>'Auth\AuthController@socialDisconnect']);
    Route::get('facebookPage/list',[
        'as' => 'social::facebook-page-list',
        'uses' => 'Auth\AuthController@getFacebookPages'
    ]);
    Route::post('/facebook-page/{page_id}/connect',[
        'as' => 'social::connect-facebook-page',
        'uses'=> 'Auth\AuthController@postConnectFacebookPage'
    ]);


    Route::get('confirm-email',['as'=>'email::confirmation', 'uses'=>'Auth\AuthController@confirmEmail']);
    Route::post('confirmation/resend',['as' => 'confirmation::resend', 'uses' => 'Auth\AuthController@resendConfirmation']);

    Route::get('password-settings', ['as'=> 'password::settings', 'uses' => 'Auth\AuthController@getPasswordSettings']);
    Route::post('password-settings', ['as' => 'password::settings', 'uses' => 'Auth\AuthController@postPasswordSettings']);





//
//    Route::post('link-profile','Auth\AuthController@sendEmailConfirmationToLinkProfile');
//    Route::get('link-email-confirmation','Auth\AuthController@confirmProfileLinkEmail');
//    Route::post('create-account','Auth\AuthController@sendEmailConfirmationToCreateAccount');
//    Route::get('create-email-confirmation','Auth\AuthController@confirmCreateAccountEmail');
//    Route::post('set-email','Auth\AuthController@setEmail');
//
//    Route::get('twitter-email-confirmation','Auth\AuthController@confirmTwitterEmail');
//    Route::post('send-twitter-link-confirmation','Auth\AuthController@sendTwitterLinkVerification');
//    Route::get('twitter-link-email-confirmation','Auth\AuthController@confirmTwitterAccountLink');

    Route::get('testing','Auth\AuthController@test');

});




