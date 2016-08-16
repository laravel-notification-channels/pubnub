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
     * Title of the push notification
     *
     * @var string
     */
    public $title;

    /**
     * Body of the push notification
     *
     * @var string
     */
    public $body;

    /**
     * The sound of the push notification
     *
     * @var string
     */
    public $sound;

    /**
     * The icon used for the push notification (Android)
     *
     * @var string
     */
    public $icon;

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
     * Sets the platform to android
     *
     * @return  $this
     */
    public function android()
    {
        $this->platform = 'android';

        return $this;
    }

    /**
     * Sets the title of the push notification
     *
     * @param   string  $title
     * @return  $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Sets the body of the push notification
     *
     * @param   string  $body
     * @return  $this
     */
    public function body($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Sets the sound of the push notification
     *
     * @param   string  $sound
     * @return  $this
     */
    public function sound($sound)
    {
        $this->sound = $sound;

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
     * Sets the icon to use for the push notification
     *
     * @param   string  $icon
     * @return  $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;

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
     * Sets the message used to create the Android push notification
     *
     * @param   PubnubMessage   $message
     * @return  $this
     */
    public function withAndroid(PubnubMessage $message)
    {
        $this->extras->push($message->android());

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
            case 'android':
                return $this->toAndroid();
        }

        $payload = [];

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
                    'alert' => [
                        'title' => $this->title,
                        'body' => $this->body,
                    ],
                    'badge' => $this->badge,
                    'sound' => $this->sound,
                ],
            ],
        ];
    }

    /**
     * Transforms the message into a payload suitable for GCM
     *
     * @return  array
     */
    protected function toAndroid()
    {
        return [
            'pn_gmc' => [
                'data' => [
                    'title' => $this->title,
                    'body' => $this->body,
                    'sound' => $this->sound,
                    'icon' => $this->icon,
                ],
            ],
        ];
    }
}
