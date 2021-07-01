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
                $pnconf = new PNConfiguration();
                $pubnub = new PubNub($pnconf);
                $pnconf->setSubscribeKey($config['subscribe_key']);
                $pnconf->setPublishKey($config['publish_key']);
                $pnconf->setUuid("avoo-crm-admin");
                return $pubnub;
            });
    }
}
