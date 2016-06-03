<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'  => App\User::class,
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID','3bfeebdba70cd35fd349'),
        'client_secret' => env('GITHUB_CLIENT_SECRET','ef993b67b3fbe622346c1c5b23c786e5ea5e4ffe'),
        'redirect' => env('GITHUB_CALLBACK_URL','http://crowdify.tech/auth/github/callback'),
    ],
    'twitter' => [
        "client_id" => env('TWITTER_CLIENT_ID',"t1CiThLhBjhEOy3LFjkIjb9Ib"),
        "client_secret" => env('TWITTER_CLIENT_SECRET',"o4b0RsjgOi1fwpxrURruWKFCuLKuNjUkXZd0uH2VOOWf84UCEd"),
        'redirect' => env('TWITTER_CALLBACK_URL','http://crowdify.tech/auth/twitter/callback'),
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID',"1480160395612910"),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET',"bf5cf46b8187625ef3634a4bdc4f7543"),
        'redirect' => env('FACEBOOK_CALLBACK_URL','http://crowdify.tech/auth/facebook/callback'),
    ],
    'facebookPage' => [
        'client_id' => env('FACEBOOK_CLIENT_ID',"1480160395612910"),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET',"bf5cf46b8187625ef3634a4bdc4f7543"),
        'redirect' => env('FACEBOOK_PAGE_CALLBACK_URL','http://crowdify.tech/auth/facebookPage/callback'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID',"737938879005-mpppcla7j7gsi9dim43j9ktuus4j1rur.apps.googleusercontent.com"),
        'client_secret' => env('GOOGLE_CLIENT_SECRET',"mbxmoc7p9PyF1GpykpLkt7Gq"),
        'redirect' => env('GOOGLE_CALLBACK_URL','http://crowdify.tech/auth/google/callback'),
    ],
    'youtube' => [
        'client_id' => env('YOUTUBE_CLIENT_ID',"737938879005-mpppcla7j7gsi9dim43j9ktuus4j1rur.apps.googleusercontent.com"),
        'client_secret' => env('YOUTUBE_CLIENT_SECRET',"mbxmoc7p9PyF1GpykpLkt7Gq"),
        'redirect' => env('YOUTUBE_CALLBACK_URL','http://crowdify.tech/auth/youtube/callback'),
    ],
    'blogger' => [
        'client_id' => env('BLOGGER_CLIENT_ID',"737938879005-mpppcla7j7gsi9dim43j9ktuus4j1rur.apps.googleusercontent.com"),
        'client_secret' => env('BLOGGER_CLIENT_SECRET',"mbxmoc7p9PyF1GpykpLkt7Gq"),
        'redirect' => env('BLOGGER_CALLBACK_URL','http://crowdify.tech/auth/blogger/callback'),
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID',"7552oj3b4wpfi0"),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET',"inuB7rHIjNLq5eXP"),
        'redirect' => env('LINKEDIN_CALLBACK_URL','http://crowdify.tech/auth/linkedin/callback/'),
        'response_type' => 'code',
    ],
    'foursquare' => [
        'client_id' => env('FOURSQUARE_CLIENT_ID',"IDWHZ5VZLFWXNBSE0WAJMT1CQT0CCSLCZPBE5HQFLABS4SZX"),
        'client_secret' => env('FOURSQUARE_CLIENT_SECRET',"mbxmoc7p9PyF1GpykpLkt7Gq"),
        'redirect' => env('FOURSQUARE_CALLBACK_URL','http://crowdify.tech/auth/foursquare/callback'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID',"IDWHZ5VZLFWXNBSE0WAJMT1CQT0CCSLCZPBE5HQFLABS4SZX"),
        'client_secret' => env('PAYPAL_SECRET',"mbxmoc7p9PyF1GpykpLkt7Gq"),
        'redirect' => env('PAYPAL_CALLBACK_URL','http://crowdify.tech/auth/paypal/callback'),
    ],

    'flickr' => [
        'client_id' => env('FLICKR_CLIENT_ID',"377ad2524ed9dae7fd1bc9305429a63f"),
        'client_secret' => env('FLICKR_CLIENT_SECRET',"4c32e9791eb5ff10"),
        'redirect' => env('FLICKR_CALLBACK_URL','http://crowdify.tech/auth/flickr/callback'),
        'perms'=> 'read'
    ],
    'instagram' => [
        'client_id' => env('INSTAGRAM_CLIENT_ID',"2607df6cb7274b37adfbcd65b85f98ea"),
        'client_secret' => env('INSTAGRAM_CLIENT_SECRET',"5365c8df8b10440fbd15e1f16302cb68"),
        'redirect' => env('INSTAGRAM_CALLBACK_URL','http://crowdify.tech/auth/instagram/callback'),
        'response_type' => 'code',
    ],

    'pinterest' => [
        'client_id' => env('PINTEREST_CLIENT_ID',"4793868280947282496"),
        'client_secret' => env('PINTEREST_CLIENT_SECRET',"c1049efa2143c6ff2ab8ba958ec03f2f1eb5f361c82532bd9db5d71ea0de4257"),
        'redirect' => env('PINTEREST_CALLBACK_URL','https://crowdify.tech/auth/pinterest/callback'),
        'response_type' => 'code',
    ],
    'tumblr' => [
        "client_id" => env('TUMBLR_CLIENT_ID',"lk0mitOif7U7sQ1yc6HMPEeLmH83eCa4TYnnzpvzdtOoZI73rU"),
        "client_secret" => env('TUMBLR_CLIENT_SECRET',"BF34Imid5WbU1mfLrEwwep61s8kdy19l4IRW718C8ECCFzF25h"),
        'redirect' => env('TUMBLR_CALLBACK_URL','http://crowdify.tech/auth/tumblr/callback'),
    ],


    'klout' => [
        'api_key' => env('KLOUT_API_KEY','dswj4u476pmmqta99fyrk33p'),
        'api_version' => env('KLOUT_API_VERSION','v2')
    ],




];
