<?php

namespace Doctrine\Tests\Common;

use Doctrine\Common\EventManager;
use Doctrine\Common\EventArgs;

class EventManagerTest extends \Doctrine\Tests\DoctrineTestCase
{
    /* Some pseudo events */
    private const PRE_FOO  = 'preFoo';
    private const POST_FOO = 'postFoo';
    private const PRE_BAR  = 'preBar';

    private $_preFooInvoked  = false;
    private $_postFooInvoked = false;

    private $_eventManager;

    protected function setUp()
    {
        $this->_eventManager   = new EventManager;
        $this->_preFooInvoked  = false;
        $this->_postFooInvoked = false;
    }

    public function testInitialState()
    {
        self::assertEquals([], $this->_eventManager->getListeners());
        self::assertFalse($this->_eventManager->hasListeners(self::PRE_FOO));
        self::assertFalse($this->_eventManager->hasListeners(self::POST_FOO));
    }

    public function testAddEventListener()
    {
        $this->_eventManager->addEventListener(['preFoo', 'postFoo'], $this);
        self::assertTrue($this->_eventManager->hasListeners(self::PRE_FOO));
        self::assertTrue($this->_eventManager->hasListeners(self::POST_FOO));
        self::assertEquals(1, count($this->_eventManager->getListeners(self::PRE_FOO)));
        self::assertEquals(1, count($this->_eventManager->getListeners(self::POST_FOO)));
        self::assertEquals(2, count($this->_eventManager->getListeners()));
    }

    public function testDispatchEvent()
    {
        $this->_eventManager->addEventListener(['preFoo', 'postFoo'], $this);
        $this->_eventManager->dispatchEvent(self::PRE_FOO);
        self::assertTrue($this->_preFooInvoked);
        self::assertFalse($this->_postFooInvoked);
    }

    public function testRemoveEventListener()
    {
        $this->_eventManager->addEventListener(['preBar'], $this);
        self::assertTrue($this->_eventManager->hasListeners(self::PRE_BAR));
        $this->_eventManager->removeEventListener(['preBar'], $this);
        self::assertFalse($this->_eventManager->hasListeners(self::PRE_BAR));
    }

    public function testAddEventSubscriber()
    {
        $eventSubscriber = new TestEventSubscriber();
        $this->_eventManager->addEventSubscriber($eventSubscriber);
        self::assertTrue($this->_eventManager->hasListeners(self::PRE_FOO));
        self::assertTrue($this->_eventManager->hasListeners(self::POST_FOO));
    }

    /* Listener methods */

    public function preFoo(EventArgs $e)
    {
        $this->_preFooInvoked = true;
    }

    public function postFoo(EventArgs $e)
    {
        $this->_postFooInvoked = true;
    }
}

class TestEventSubscriber implements \Doctrine\Common\EventSubscriber
{
    public function getSubscribedEvents()
    {
        return ['preFoo', 'postFoo'];
    }
}
