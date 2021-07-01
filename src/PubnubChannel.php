<?php

namespace NotificationChannels\Pubnub;

use PubNub\PubNub;
use Pubnub\PubNubException;
use Illuminate\Notifications\Notification;
use NotificationChannels\Pubnub\Exceptions\CouldNotSendNotification;

class PubnubChannel
{
    /** @\Pubnub\Pubnub */
    protected $pubnub;

    public function __construct(PubNub $pubnub)
    {
        $this->pubnub = $pubnub;
    }

    /**
     * Send the given notification.
     *
     * @param   mixed   $notifiable
     * @param   \Illuminate\Notifications\Notification  $notification
     *
     * @throws  \NotificationChannels\Pubnub\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toPubnub($notifiable);

        $channel = ! is_null($message->channel) ? $message->channel : $notifiable->routeNotificationFor('pubnub');

        if (is_null($channel)) {
            throw CouldNotSendNotification::missingChannel();
        }

        try {
            $this->pubnub->publish($channel, $message->toArray(), $message->storeInHistory);
        } catch (PubNubException $exception) {
            throw CouldNotSendNotification::pubnubRespondedWithAnError($exception);
        }
    }
}
