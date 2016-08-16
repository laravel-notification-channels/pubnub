<?php

namespace NotificationChannels\Pubnub\Test;

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
}