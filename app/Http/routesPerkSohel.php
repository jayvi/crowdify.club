<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/20/15
 * Time: 4:38 PM
 */

Route::get('/',['as'=> 'perk::home', 'uses'=> 'Perk\WelcomeController@index' ]);
Route::get('/{username}',['as'=> 'perk::public_profile','uses' => 'ProfileController@getPublicProfile']);
Route::get('/{username}/status/{statusId}',['as'=> 'perk::publicStatus','uses' => 'ProfileController@getPublicStatus']);
Route::post('/{username}/status/{statusId}',['as'=> 'perk::publicStatus','uses' => 'ProfileController@postPublicStatusComment']);

Route::group(['prefix'=>'/sponsor', 'as' => 'sponsor::'], function(){
    Route::get('/{username}', ['as' => 'public_profile', 'uses' => 'ProfileController@getSponsorPublicProfile']);
});



Route::group(['prefix'=> 'profile','as' => 'profile::'], function(){

    Route::get('/followers',[
        'as' => 'followers',
        'uses' => 'ProfileController@getFollowers'
    ]);

    Route::post('/follow',[
        'as' => 'follow',
        'uses' => 'ProfileController@follow'
    ]);

    Route::post('/un-follow',[
        'as' => 'unfollow',
        'uses' => 'ProfileController@unfollow'
    ]);

    Route::post('/block',[
        'middleware' => 'admin',
        'as' => 'block',
        'uses' => 'ProfileController@block'
    ]);

    Route::post('/un-block',[
        'middleware' => 'admin',
        'as' => 'un-block',
        'uses' => 'ProfileController@unBlock'
    ]);


    Route::group(['prefix' => 'survey', 'as' => 'survey'], function(){
        //Route::get('account-details', ['as' => 'account-details', 'uses' => '']);
    });


    Route::get('/edit', ['as'=> 'edit', 'uses'=>'ProfileController@getEdit']);
    Route::post('/edit', ['as'=> 'edit', 'uses'=>'ProfileController@postEdit']);
    Route::post('/upload-avatar', ['as'=> 'upload::avatar','uses'=> 'ProfileController@postUploadAvatar']);

    Route::get('/confirm-settings',['as' => 'confirm-settings','uses'=> 'ProfileController@getConfirmSettings']);
    Route::post('/confirm-settings',['as' => 'confirm-settings','uses'=> 'ProfileController@postConfirmSettings']);

    Route::group(['prefix' => 'item', 'as' => 'item::'], function(){
        Route::post('invest', ['as' => 'invest', 'uses' => 'ProfileController@invest']);
        Route::post('sell', ['as' => 'sell', 'uses' => 'ProfileController@sell']);
    });

    Route::post('profile/tweet',['as'=>'tweet', 'uses' => 'ProfileController@tweet']);

    Route::get('opt-out',[
        'as'=> 'opt-out',
        'uses' => 'ProfileController@getOptOut'
    ]);
    Route::post('opt-out',[
        'as'=> 'opt-out',
        'uses' => 'ProfileController@postOptOut'
    ]);

    Route::post('/{item_id}/upload-photo',['as' => 'item::upload-photo','uses' => 'ProfileController@uploadItemPhoto']);
});

// perks
Route::group(['namespace' => 'Perk','prefix'=> 'leaderboard', 'as' => 'leaderboard::'], function(){
    Route::get('/home', ['as' => 'home', 'uses' => 'LeaderboardController@index']);
});

// perks
Route::group(['namespace'=>'Perk','prefix'=> 'home', 'as' => 'perk::'], function(){
    Route::get('/tasks',['as' => 'tasks', 'uses' => 'WelcomeController@getTasks']);
    Route::get('/perks',['as' => 'perks', 'uses' => 'WelcomeController@getPerks']);
    Route::get('/events',['as' => 'events', 'uses' => 'WelcomeController@getEvents']);

});

Route::group(['namespace' => 'Subscriptions','prefix'=> 'perk/membership', 'as' => 'subscriptions::'], function() {
    Route::get('/', [
        'as' => 'home',
        'uses' => 'SubscriptionsController@index',
    ]);
    Route::post('payment', array(
        'as' => 'payment',
        'uses' => 'SubscriptionsController@postPayment',
    ));
    Route::post('bitcoin', array(
        'as' => 'bitcoin',
        'uses' => 'SubscriptionsController@bitPayment',
    ));

// this is after make the payment, PayPal redirect back to your site
    Route::get('payment/status', array(
        'as' => 'payment::status',
        'uses' => 'SubscriptionsController@getPaymentStatus',
    ));

    Route::post('payment/suspend',[
        'as' => 'payment::suspend',
        'uses' => 'SubscriptionsController@postSuspendAgreement'
    ]);
    Route::post('payment/cancel',[
        'as' => 'payment::cancel',
        'uses' => 'SubscriptionsController@postCancelAgreement'
    ]);
    Route::post('payment/re-activate',[
        'as' => 'payment::reactivate',
        'uses' => 'SubscriptionsController@postReactivateAgreement'
    ]);

});

Route::group(['namespace'=>'Webhook','prefix'=>'paypal','as' => 'paypal::'],function(){
    Route::post('notifications',[
        'as' => 'notifications',
        'uses' => 'PaypalWebhookController@index'
    ]);
});

Route::group(['prefix' => 'email/broadcaster','as' => 'broadcaster::'], function(){
    Route::get('/',[
        'as' => 'home',
        'uses' => 'EmailBroadcastController@index'
    ]);
    Route::post('/',[
        'as' => 'broadcast',
        'uses' => 'EmailBroadcastController@broadCast'
    ]);
});

Route::group(['prefix' => 'users', 'as' => 'users::'], function(){
    Route::get('list',[
        'as' => 'list',
        'uses' => 'UserController@index'
    ]);
});
Route::group(['namespace' =>'Affiliate', 'prefix' => 'moneytree', 'as' => 'money::'], function(){
    Route::get('/{username}',[
        'as' => 'tree',
        'uses' => 'AffiliateController@getDashboard'
    ]);
    Route::post('/postaff',[
        'as' => 'postaff',
        'uses' => 'AffiliateController@postaff',
    ]);
});


Route::group(['prefix' => 'admin', 'as' => 'admin::'], function(){
    Route::get('/premium-members', ['as'=>'premium-members', 'uses' => 'UserController@getPremiumMembers']);
});

Route::group(['namespace' =>'Affiliate', 'prefix' => 'affiliates', 'as' => 'affiliates::'], function(){
    Route::get('/dashboard/{username}',[
        'as' => 'dashboard',
        'uses' => 'AffiliateController@getDashboard'
    ]);
    Route::get('/admin-dashboard',[
        'as' => 'admin-dashboard',
        'uses'=>'AffiliateController@getAdminAffiliateDashboard',
    ]);
    Route::get('/top',[
        'as' => 'top',
        'uses'=>'AffiliateController@getTopAffiliates',
    ]);
    Route::get('/new',[
        'as' => 'new',
        'uses'=>'AffiliateController@getNewAffiliates',
    ]);
    Route::post('/postaff',[
        'as' => 'postaff',
        'uses' => 'AffiliateController@postaff',
    ]);
});






Route::get('crowdify/testing', function(){
    dd((new \App\Services\Klout\KloutService())->getScoreByTwitterName('SohelTechnext'));
});


