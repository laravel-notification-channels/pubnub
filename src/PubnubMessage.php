<?php

namespace NotificationChannels\Pubnub;

use InvalidArgumentException;

class PubnubMessage
{
    const PLATFORM_iOS = 'iOS';

    const PLATFORM_ANDROID = 'android';

    const PLATFORM_WINDOWS = 'windows';

    /**
     * Platform the push notification is using.
     *
     * @var string
     */
    public $platform;

    /**
     * Channel the message should be sent to.
     *
     * @var string
     */
    public $channel;

    /**
     * If the message should be stored in the Pubnub history.
     *
     * @var bool
     */
    public $storeInHistory = true;

    /**
     * The number to display on the push notification badge (iOS).
     *
     * @var int
     */
    public $badge;

    /**
     * Title of the push notification.
     *
     * @var string
     */
    public $title;

    /**
     * Body of the push notification.
     *
     * @var string
     */
    public $body;

    /**
     * The sound of the push notification (iOS, Android).
     *
     * @var string
     */
    public $sound;

    /**
     * The icon used for the push notification (Android).
     *
     * @var string
     */
    public $icon;

    /**
     * The type of notification (Windows).
     *
     * @var string
     */
    public $type;

    /**
     * The delay in seconds for delivering the push notification (Windows).
     *
     * @var int
     */
    public $delay = 0;

    /**
     * Collection of PubnubMessage instances used for push notification platforms.
     *
     * @var \Illuminate\Support\Collection<PubnubMessage>
     */
    protected $extras;

    /**
     * Extra data to add to the payload.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Extra options to add to the push notification.
     *
     * @var array
     */
    protected $options = [];

    public function __construct()
    {
        $this->extras = collect();
    }

    /**
     * Set the channel the message should be sent to.
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
     * Set the option to store the current message in the Pubnub history.
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
     * Sets the platform to iOS.
     *
     * @return  $this
     */
    public function iOS()
    {
        $this->platform = static::PLATFORM_iOS;

        return $this;
    }

    /**
     * Sets the platform to android.
     *
     * @return  $this
     */
    public function android()
    {
        $this->platform = static::PLATFORM_ANDROID;

        return $this;
    }

    /**
     * Sets the platform to windows.
     *
     * @return  $this
     */
    public function windows()
    {
        $this->platform = static::PLATFORM_WINDOWS;

        return $this;
    }

    /**
     * Sets the title of the push notification.
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
     * Sets the body of the push notification.
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
     * Sets the sound of the push notification.
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
     * Sets the number to display on the push notification badge (iOS).
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
     * Sets the icon to use for the push notification.
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
     * Sets the type of notification (Windows).
     *
     * @throws \InvalidArgumentException
     * @param   string  $type
     * @return  $this
     */
    public function type($type)
    {
        if (! in_array($type, ['toast', 'flip', 'cycle', 'iconic'])) {
            throw new InvalidArgumentException("Invalid type given [{$type}]. Expected 'toast', 'flip', 'cycle' or 'iconic'.");
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Sets the delay for delivering the notification (Windows).
     *
     * @param   int $delay
     * @return  $this
     */
    public function delay($delay)
    {
        if (! in_array($delay, [0, 450, 900])) {
            throw new InvalidArgumentException("Invalid delay give [{$delay}]. Expected 0, 450 or 900.");
        }

        $this->delay = $delay;

        return $this;
    }

    /**
     * Sets optional extra data to add to the payload.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Sets optional extra options onto the push notification payload (W.
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * @return array
     */
    protected function getData()
    {
        if ($this->platform === static::PLATFORM_iOS) {
            return $this->data;
        }

        return array_merge($this->data, $this->options);
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets the message used to create the iOS push notification.
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
     * Sets the message used to create the Android push notification.
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
     * Sets the message used to create the Windows push notification.
     *
     * @param   PubnubMessage   $message
     * @return  $this
     */
    public function withWindows(PubnubMessage $message)
    {
        $this->extras->push($message->windows());

        return $this;
    }

    /**
     * Transforms the message into an suitable payload for Pubnub\Pubnub.
     *
     * @return  array
     */
    public function toArray()
    {
        switch ($this->platform) {
            case 'iOS':
                return $this->toiOS();
            case 'android':
                return $this->toAndroid();
            case 'windows':
                return $this->toWindows();
        }

        $payload = array_merge($this->data, [
            'body' => $this->body,
            'title' => $this->title,
        ]);

        $this->extras->each(function (PubnubMessage $message) use (&$payload) {
            $payload = array_merge($payload, $message->toArray());
        });

        return $payload;
    }

    /**
     * Transforms the message into an array suitable for the payload.
     *
     * @return  array
     */
    protected function toiOS()
    {
        return [
            'pn_apns' => array_merge($this->getData(), [
                'aps' => array_merge($this->getOptions(), [
                    'alert' => [
                        'title' => $this->title,
                        'body' => $this->body,
                    ],
                    'badge' => $this->badge,
                    'sound' => $this->sound,
                ]),
            ]),
        ];
    }

    /**
     * Transforms the message into a payload suitable for GCM.
     *
     * @return  array
     */
    protected function toAndroid()
    {
        return [
            'pn_gmc' => [
                'data' => array_merge($this->getData(), [
                    'title' => $this->title,
                    'body' => $this->body,
                    'sound' => $this->sound,
                    'icon' => $this->icon,
                ]),
            ],
        ];
    }

    /**
     * Transforms the message into a payload suitable for MPNS.
     *
     * @return  array
     */
    protected function toWindows()
    {
        return [
            'pn_mpns' => array_merge($this->getData(), [
                'title' => $this->title,
                'body' => $this->body,
                'type' => $this->type,
                'delay' => $this->delay,
            ]),
        ];
    }
}
