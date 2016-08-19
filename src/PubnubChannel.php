<?php

namespace NotificationChannels\Pubnub;

use NotificationChannels\Pubnub\Exceptions\CouldNotSendNotification;
use Illuminate\Notifications\Notification;
use Pubnub\Pubnub;
use Pubnub\PubnubException;

class PubnubChannel
{
    /** @\Pubnub\Pubnub */
    protected $pubnub;

    public function __construct(Pubnub $pubnub)
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
        } catch (PubnubException $exception) {
            throw CouldNotSendNotification::pubnubRespondedWithAnError($exception);
        }
    }
}
