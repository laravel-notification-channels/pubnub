<?php

namespace NotificationChannels\Pubnub;

use Illuminate\Events\Dispatcher as EventDispatcher;
use NotificationChannels\Pubnub\Exceptions\CouldNotSendNotification;
use NotificationChannels\Pubnub\Events\MessageWasSent;
use NotificationChannels\Pubnub\Events\SendingMessage;
use Illuminate\Notifications\Notification;
use Pubnub\Pubnub;
use Pubnub\PubnubException;

class PubnubChannel
{
    /** @\Pubnub\Pubnub */
    protected $pubnub;

    /** @var EventDispatcher */
    protected $event;

    public function __construct(Pubnub $pubnub, EventDispatcher $event)
    {
        $this->pubnub = $pubnub;
        $this->event = $event;
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
        if ( ! $this->shouldSendMessage($notifiable, $notification)) return;

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

        $this->event->fire(new MessageWasSent($notifiable, $notification));
    }

    /**
     *
     *
     * @param   mixed   $notifiable
     * @param   Notification    $notification
     * @return  bool
     */
    protected function shouldSendMessage($notifiable, Notification $notification)
    {
        return $this->event->fire(new SendingMessage($notifiable, $notification), [], true) !== false;
    }
}
