<?php

namespace NotificationChannels\Pubnub;

use PubNub\PubNub;
use Illuminate\Support\ServiceProvider;

class PubnubServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(PubnubChannel::class)
            ->needs(PubNub::class)
            ->give(function () {
                $config = config('services.pubnub');

                return new PubNub(
                    $config['publish_key'],
                    $config['subscribe_key'],
                    $config['secret_key']
                );
            });
    }
}
