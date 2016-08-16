<?php

namespace NotificationChannels\Pubnub\Test;

use Illuminate\Notifications\Notification;
use Mockery;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\Pubnub\PubnubChannel;
use NotificationChannels\Pubnub\PubnubMessage;
use PHPUnit_Framework_TestCase;
use Pubnub\Pubnub;

class ChannelTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->notification = new TestNotification;

        $this->notifiable = new TestNotifiable;

        $this->pubnub = Mockery::mock(Pubnub::class);

        $this->channel = new PubnubChannel($this->pubnub);
    }

    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->pubnub->shouldReceive('publish')->with('my_channel');

        $this->channel->send($this->notifiable, $this->notification);
    }
}

class TestNotifiable
{
    use Notifiable;

    public function routeNotificationForPubnub()
    {
        return 'my_channel';
    }
}

class TestNotification extends Notification
{
    public function toPubnub($notifiable)
    {
        return new PubnubMessage();
    }
}