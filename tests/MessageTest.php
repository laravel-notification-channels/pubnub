<?php

namespace NotificationChannels\Pubnub\Test;

use InvalidArgumentException;
use Illuminate\Support\Arr;
use NotificationChannels\Pubnub\PubnubMessage;
use PHPUnit_Framework_TestCase;

class MessageTest extends PHPUnit_Framework_TestCase
{
    /** @var \NotificationChannels\Pubnub\PubnubMessage */
    protected $message;

    public function setUp()
    {
        parent::setUp();

        $this->message = new PubnubMessage();
    }

    /** @test */
    public function it_can_set_the_channel()
    {
        $this->message->channel('alpha');

        $this->assertEquals('alpha', $this->message->channel);
    }

    /** @test */
    public function it_can_set_the_title()
    {
        $this->message->title('the title');

        $this->assertEquals('the title', Arr::get($this->message->iOS()->toArray(), 'pn_apns.aps.alert.title'));
        $this->assertEquals('the title', Arr::get($this->message->android()->toArray(), 'pn_gmc.data.title'));
        $this->assertEquals('the title', Arr::get($this->message->windows()->toArray(), 'pn_mpns.title'));
    }

    /** @test */
    public function it_can_set_the_body()
    {
        $this->message->body('the content');

        $this->assertEquals('the content', Arr::get($this->message->iOS()->toArray(), 'pn_apns.aps.alert.body'));
        $this->assertEquals('the content', Arr::get($this->message->android()->toArray(), 'pn_gmc.data.body'));
        $this->assertEquals('the content', Arr::get($this->message->windows()->toArray(), 'pn_mpns.body'));
    }

    /** @test */
    public function it_can_control_the_history_storage()
    {
        $this->message->storeInHistory(false);

        $this->assertFalse($this->message->storeInHistory);
    }

    /** @test */
    public function it_can_set_the_badge()
    {
        $this->message->badge(1);

        $this->assertEquals(1, Arr::get($this->message->iOS()->toArray(), 'pn_apns.aps.badge'));
    }

    /** @test */
    public function it_can_set_the_platform_for_iOS()
    {
        $this->message->iOS();

        $this->assertTrue(Arr::has($this->message->toArray(), 'pn_apns.aps'));
    }

    /** @test */
    public function it_can_set_the_platform_for_android()
    {
        $this->message->android();

        $this->assertTrue(Arr::has($this->message->toArray(), 'pn_gmc.data'));
    }

    /** @test */
    public function it_can_set_the_platform_for_windows()
    {
        $this->message->windows();

        $this->assertTrue(Arr::has($this->message->toArray(), 'pn_mpns'));
    }

    /** @test */
    public function it_can_set_the_sound()
    {
        $this->message->sound('my-sound');

        $this->assertEquals('my-sound', Arr::get($this->message->iOS()->toArray(), 'pn_apns.aps.sound'));
        $this->assertEquals('my-sound', Arr::get($this->message->android()->toArray(), 'pn_gmc.data.sound'));
    }

    /** @test */
    public function it_can_set_the_icon()
    {
        $this->message->icon('my-icon');

        $this->assertEquals('my-icon', Arr::get($this->message->android()->toArray(), 'pn_gmc.data.icon'));
    }

    /** @test */
    public function it_can_set_the_type()
    {
        $this->message->type('toast');

        $this->assertEquals('toast', Arr::get($this->message->windows()->toArray(), 'pn_mpns.type'));
    }

    /** @test */
    public function it_will_throw_an_exception_when_the_wrong_type_is_used()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $this->message->type('invalid');
    }

    /** @test */
    public function it_can_set_the_delay()
    {
        $this->message->delay(450);

        $this->assertEquals(450, Arr::get($this->message->windows()->toArray(), 'pn_mpns.delay'));
    }

    /** @test */
    public function it_will_throw_an_exception_when_the_wrong_delay_is_used()
    {
        $this->setExpectedException(InvalidArgumentException::class);

        $this->message->delay(1000);
    }

    /** @test */
    public function it_will_set_a_default_delay()
    {
        $this->assertEquals(0, Arr::get($this->message->windows()->toArray(), 'pn_mpns.delay'));
    }

    /** @test */
    public function it_can_send_push_notifications_to_multiple_platforms()
    {
        $this->message
            ->body('My Body')
            ->withiOS(new PubnubMessage())
            ->withAndroid(new PubnubMessage())
            ->withWindows(new PubnubMessage());

        $this->assertTrue(Arr::has($this->message->toArray(), 'pn_apns.aps'));
        $this->assertTrue(Arr::has($this->message->toArray(), 'pn_gmc.data'));
        $this->assertTrue(Arr::has($this->message->toArray(), 'pn_mpns'));
    }
}