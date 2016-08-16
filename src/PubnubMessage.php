<?php

namespace NotificationChannels\Pubnub;

use Pubnub\Pubnub;

class PubnubMessage
{
    /**
     * Platform the push notification is using
     *
     * @var string
     */
    public $platform;

    /**
     * Channel the message should be sent to
     *
     * @var string
     */
    public $channel;

    /**
     * Content of the message
     *
     * @var string
     */
    public $content;

    /**
     * If the message should be stored in the Pubnub history
     *
     * @var bool
     */
    public $storeInHistory = true;

    /**
     * The number to display on the push notification badge (iOS)
     *
     * @var int
     */
    public $badge;

    /**
     * The title to display on the push notification (iOS)
     *
     * @var string
     */
    public $alert;

    /**
     * Collection of PubnubMessage instances used for push notification platforms
     *
     * @var \Illuminate\Support\Collection<PubnubMessage>
     */
    protected $extras;

    public function __construct()
    {
        $this->extras = collect();
    }

    /**
     * Set the channel the message should be sent to
     *
     * @param   string  $channel
     * @return  $this
     */
    public function channel($channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Set the content the message should contain
     *
     * @param   string|array  $content
     * @return  $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the option to store the current message in the Pubnub history
     *
     * @param   bool    $shouldStore
     * @return  $this
     */
    public function storeInHistory($shouldStore = true)
    {
        $this->storeInHistory = (bool) $shouldStore;

        return $this;
    }

    /**
     * Sets the platform to iOS
     *
     * @return  $this
     */
    public function iOS()
    {
        $this->platform = 'iOS';

        return $this;
    }

    /**
     * Sets the alert to display on the push notification (iOS)
     *
     * @param   string  $alert
     * @return  $this
     */
    public function alert($alert)
    {
        $this->alert = $alert;

        return $this;
    }

    /**
     * Sets the number to display on the push notification badge (iOS)
     *
     * @param   int $badge
     * @return  $this
     */
    public function badge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Sets the message used to create the iOS push notification
     *
     * @param   PubnubMessage   $message
     * @return  $this
     */
    public function withiOS(PubnubMessage $message)
    {
        $this->extras->push($message->iOS());

        return $this;
    }

    /**
     * Transforms the message into an suitable payload for Pubnub\Pubnub
     *
     * @return  array
     */
    public function toArray()
    {
        switch($this->platform) {
            case 'iOS':
                return $this->toiOS();
        }

        $payload = [
            'content' => $this->content,
        ];

        $this->extras->each(function(PubnubMessage $message) use (&$payload)
        {
            $payload = array_merge($payload, $message->toArray());
        });

        return $payload;
    }

    /**
     * Transforms the message into an array suitable for the payload
     *
     * @return  array
     */
    protected function toiOS()
    {
        return [
            'pn_apns' => [
                'aps' => [
                    'alert' => $this->alert,
                    'badge' => $this->badge,
                ],
            ],
        ];
    }
}
