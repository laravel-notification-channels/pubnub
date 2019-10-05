<?php

namespace NotificationChannels\Pubnub\Test;

use Mockery;
use Pubnub\Pubnub;
use PHPUnit\Framework\TestCase;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Pubnub\PubnubChannel;
use NotificationChannels\Pubnub\PubnubMessage;
use NotificationChannels\Pubnub\Exceptions\CouldNotSendNotification;

class ChannelTest extends TestCase
{
    /** @var Pubnub */
    protected $pubnub;

    /** @var PubnubChannel */
    protected $channel;

    public function setUp(): void
    {
        $this->pubnub = Mockery::mock(Pubnub::class);
        $this->channel = new PubnubChannel($this->pubnub);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification_using_notifiable_routing()
    {
        $this->pubnub->shouldReceive('publish')
            ->once()
            ->with(
                'my_routed_channel',
                [
                    'title' => 'Hello World',
                    'body' => 'Hello Darkness My Old Friend',
                ],
                true
            );

        $this->channel->send(new TestRoutedNotifiable(), new TestRoutedNotification());
    }

    /** @test */
    public function it_can_send_a_notification_using_the_channel_method()
    {
        $this->pubnub->shouldReceive('publish')
            ->once()
            ->with(
                'my_nonrouted_channel',
                [
                    'title' => 'Hello World',
                    'body' => 'Hello Darkness My Old Friend',
                ],
                true
            );

        $this->channel->send(new TestNotifiable(), new TestNotification());
    }

    /** @test */
    public function it_throws_an_exception_when_no_channel_is_supplied()
    {
        $this->expectException(CouldNotSendNotification::class);

        $this->pubnub->shouldReceive('publish')->never();

        $this->channel->send(new TestNotifiable(), new TestRoutedNotification());
    }
}

class TestNotifiable
{
    use Notifiable;
}

class TestRoutedNotifiable
{
    use Notifiable;

    public function routeNotificationForPubnub()
    {
        return 'my_routed_channel';
    }
}

class TestNotification extends Notification
{
    public function toPubnub($notifiable)
    {
        return (new PubnubMessage())
            ->title('Hello World')
            ->body('Hello Darkness My Old Friend')
            ->channel('my_nonrouted_channel');
    }
}

class TestRoutedNotification extends Notification
{
    public function toPubnub($notifiable)
    {
        return (new PubnubMessage())
            ->title('Hello World')
            ->body('Hello Darkness My Old Friend');
    }
}
