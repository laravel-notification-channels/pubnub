<?php

namespace NotificationChannels\Pubnub\Exceptions;

use Exception;
use PubNub\PubNubException;

class CouldNotSendNotification extends Exception
{
    public static function pubnubRespondedWithAnError(PubNubException $exception)
    {
        return new static($exception->getMessage());
    }

    public static function missingChannel()
    {
        return new static('Notification not sent. No channel specified');
    }
}
