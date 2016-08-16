<?php

namespace NotificationChannels\Pubnub\Test;

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
    public function it_can_set_the_content()
    {
        $this->message->content('the content');

        $this->assertEquals('the content', $this->message->content);
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
        $this->message->iOS()->badge(1);

        $this->assertEquals(1, Arr::get($this->message->toArray(), 'pn_apns.aps.badge'));
    }

    /** @test */
    public function it_can_set_the_alert()
    {
        $this->message->iOS()->alert('the alert');

        $this->assertEquals('the alert', Arr::get($this->message->toArray(), 'pn_apns.aps.alert'));
    }

    /** @test */
    public function it_can_set_the_platform_for_iOS()
    {
        $this->message->iOS();

        $this->assertTrue(Arr::has($this->message->toArray(), 'pn_apns.aps'));
    }
}