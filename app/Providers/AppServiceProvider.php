<?php

namespace App\Providers;



use App\Services\SocialProviders\Blogger\BloggerProvider;
use App\Services\SocialProviders\Flickr\FlickrProvider;
use App\Services\SocialProviders\Flickr\Server;
use App\Services\SocialProviders\Foursquare\FoursquareProvider;
use App\Services\SocialProviders\Instagram\InstagramProvider;
use App\Services\SocialProviders\Paypal\PaypalProvider;
use App\Services\SocialProviders\Pinterest\PinterestProvider;
use App\Services\SocialProviders\Tumblr\TumblrProvider;
use App\Services\SocialProviders\Youtube\YoutubeProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Two\FacebookProvider;
use League\OAuth1\Client\Server\Tumblr;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'foursquare',
            function ($app) use ($socialite) {
                $config = $app['config']['services.foursquare'];
                return $socialite->buildProvider(FoursquareProvider::class, $config);
            }
        );


        $socialite->extend(
            'flickr',
            function ($app) use ($socialite) {
                $config = $app['config']['services.flickr'];
                return new FlickrProvider(
                    $this->app['request'], new Server([
                            'identifier' => $config['client_id'],
                            'secret' => $config['client_secret'],
                            'callback_uri' => $config['redirect'],
                            'perms' => $config['perms']
                    ])
                );

               // return $socialite->buildProvider(FlickrProvider::class, $config,new Server($socialite->formatConfig($config)));
            }
        );

        $socialite->extend(
            'tumblr',
            function ($app) use ($socialite) {
                $config = $app['config']['services.tumblr'];
                return new TumblrProvider(
                    $this->app['request'], new Tumblr([
                        'identifier' => $config['client_id'],
                        'secret' => $config['client_secret'],
                        'callback_uri' => $config['redirect'],
                    ])
                );

                // return $socialite->buildProvider(FlickrProvider::class, $config,new Server($socialite->formatConfig($config)));
            }
        );

        $socialite->extend(
            'instagram',
            function ($app) use ($socialite) {
                $config = $app['config']['services.instagram'];
                return $socialite->buildProvider(InstagramProvider::class, $config);
            }
        );
        $socialite->extend(
            'pinterest',
            function ($app) use ($socialite) {
                $config = $app['config']['services.pinterest'];
                return $socialite->buildProvider(PinterestProvider::class, $config);
            }
        );
        $socialite->extend(
            'youtube',
            function ($app) use ($socialite) {
                $config = $app['config']['services.youtube'];
                return $socialite->buildProvider(YoutubeProvider::class, $config);
            }
        );
        $socialite->extend(
            'blogger',
            function ($app) use ($socialite) {
                $config = $app['config']['services.blogger'];
                return $socialite->buildProvider(BloggerProvider::class, $config);
            }
        );

        $socialite->extend(
            'facebookPage',
            function ($app) use ($socialite) {
                $config = $this->app['config']['services.facebookPage'];
                return $socialite->buildProvider(FacebookProvider::class, $config);
            }
        );

        $socialite->extend(
            'paypal',
            function ($app) use ($socialite) {
                $config = $this->app['config']['services.paypal'];
                return $socialite->buildProvider(PaypalProvider::class, $config);
            }
        );



    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Check if a server is given, which indicates that OAuth1 is used.
     *
     * @param string $oauth1Server
     *
     * @return bool
     */
    private function isOAuth1($oauth1Server)
    {
        return (!empty($oauth1Server));
    }
    /**
     * @param string $class
     * @param string $baseClass
     *
     * @throws InvalidArgumentException
     */
    private function classExtends($class, $baseClass)
    {
        if (false === is_subclass_of($class, $baseClass)) {
            $message = $class.' does not extend '.$baseClass;
            throw new InvalidArgumentException($message);
        }
    }
}
