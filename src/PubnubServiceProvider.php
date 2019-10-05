<?php

namespace NotificationChannels\Pubnub;

use Pubnub\Pubnub;
use Illuminate\Support\ServiceProvider;

class PubnubServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(PubnubChannel::class)
            ->needs(Pubnub::class)
            ->give(function () {
                $config = config('services.pubnub');

                return new Pubnub(
                    $config['publish_key'],
                    $config['subscribe_key'],
                    $config['secret_key']
                );
            });
    }
}
