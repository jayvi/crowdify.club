<?php

Route::group(['namespace' => 'Perk', 'prefix'=> 'api', 'as' => 'api::'], function(){

});
Route::group(['namespace' => 'Perk', 'prefix'=> 'tasks', 'as' => 'hugs::'], function(){
    Route::get('/current', ['as'=> 'home', 'uses'=>'HugController@index']);
    Route::get('/create', ['as'=> 'create', 'uses'=>'HugController@getCreate']);
    Route::post('/create', ['as'=> 'create', 'uses'=>'HugController@postCreate']);
    Route::get('/{id}/show', ['as'=> 'show', 'uses'=>'HugController@getShow']);
    Route::get('/{id}/edit', ['as'=> 'edit', 'uses'=>'HugController@getEdit']);
    Route::get('/{id}/rerun',['as'=> 'rerun', 'uses'=>'HugController@taskRerun']);
    Route::put('/{id}/edit', ['as'=> 'edit', 'uses'=>'HugController@postEdit']);
    Route::any('{id}/delete', ['as'=> 'delete', 'uses'=>'HugController@delete']);
    Route::post('/{id}/completion', ['as'=> 'completion', 'uses'=>'HugController@completion']);
    Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'HugController@hugDashboard']);
    Route::post('/completers', ['as' => 'completers', 'uses' => 'HugController@hugCompleters']);
    Route::post('/revokeCompletion', ['as' => 'revokeCompletion', 'uses' => 'HugController@revokeHugCompletion']);
    Route::post('/approveAllCompletion', ['as' => 'approveAllCompletion', 'uses' => 'HugController@approveAllHugCompletion']);

    Route::get('/completion-history',['as' => 'completion-history', 'uses' => 'HugController@getCompletionHistory']);

    Route::group(['prefix' => '{hug_id}/comment', 'as' => 'comments::'], function(){

        Route::post('/create',['as' => 'create', 'uses' => 'HugController@postComment']);
    });

    
});

Route::group(['namespace' => 'Perk', 'prefix'=> 'profile', 'as' => 'profile::'], function(){
    Route::get('/bank', ['as'=> 'bank', 'uses'=>'BankController@index']);
    Route::post('/send-gift', ['as'=> 'sendGift', 'uses'=>'BankController@sendCoin']);
    Route::post('/send-points', ['as'=> 'sendPoints', 'uses'=>'BankController@sendPoint']);
});

Route::group(['prefix' => 'profile', 'as' => 'profile::'], function(){
    Route::get('/{username}/portfolio', ['as'=> 'portfolio', 'uses'=>'ProfileController@portfolio']);
});

Route::group(['namespace' => 'Perk', 'prefix'=> 'perks', 'as' => 'perks::'], function(){
    Route::get('/home', ['as'=> 'home', 'uses'=>'PerkController@index']);
    Route::get('/{id}', ['as'=> 'perk', 'uses'=>'PerkController@perk']);
    Route::post('/create', ['as' => 'create', 'uses' => 'PerkController@create']);
    Route::get('/{id}/edit', ['as' => 'edit', 'uses' => 'PerkController@getEdit']);
    Route::post('/{id}/edit', ['as' => 'edit', 'uses' => 'PerkController@postEdit']);
    Route::delete('/{id}', ['as' => 'delete', 'uses' => 'PerkController@delete']);
});

Route::group(['middleware' => ['auth'], 'namespace' => 'Perk', 'prefix'=> 'community', 'as' => 'community::'], function(){
    Route::get('/dashboard', ['as'=> 'dashboard', 'uses'=>'CommunityController@communities']);
    Route::get('/{communitySlug}', ['as'=> 'communityPage', 'uses'=>'CommunityController@community']);
    Route::post('/{communitySlug}', ['as'=> 'communityPost', 'uses'=>'CommunityController@postCommunityPost']);

    Route::get('/{communitySlug}/post/{communityPostId}', ['as'=> 'communityPost.each', 'uses'=>'CommunityController@getEachCommunityPost']);

    Route::post('/{communitySlug}/post/{communityPostId}/comment', ['as'=> 'communityPost.comment', 'uses'=>'CommunityController@postComment']);

    Route::get('/{communitySlug}/post/{communityPostId}/delete', ['as'=> 'communityPost.delete', 'uses'=>'CommunityController@deleteCommunityPost']);
    Route::get('/{communitySlug}/post-comment/{commentId}/delete', ['as'=> 'communityPostComment.delete', 'uses'=>'CommunityController@deleteCommunityPostComment']);

    Route::group(['middleware' => 'communityAdmin'], function(){
        Route::get('/{communitySlug}/post/{communityPostId}/pin', ['as'=> 'communityPost.pin', 'uses'=>'CommunityController@pinCommunityPost']);
        Route::get('/{communitySlug}/post/{communityPostId}/un-pin', ['as'=> 'communityPost.unPin', 'uses'=>'CommunityController@unPinCommunityPost']);
        Route::get('/{communitySlug}/post/{communityPostId}/approve', ['as'=> 'communityPost.approve', 'uses'=>'CommunityController@approveCommunityPost']);
    });
});
