<?php

namespace Doctrine\Tests\Common;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\Deprecations\PHPUnit\VerifyDeprecations;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

use function array_keys;

class EventManagerTest extends TestCase
{
    use VerifyDeprecations;

    /* Some pseudo events */
    private const PRE_FOO  = 'preFoo';
    private const POST_FOO = 'postFoo';
    private const PRE_BAR  = 'preBar';

    /** @var bool */
    private $_preFooInvoked = false;

    /** @var bool */
    private $_postFooInvoked = false;

    /** @var EventManager */
    private $_eventManager;

    protected function setUp(): void
    {
        $this->_eventManager   = new EventManager();
        $this->_preFooInvoked  = false;
        $this->_postFooInvoked = false;
    }

    public function testInitialState(): void
    {
        self::assertEquals([], $this->_eventManager->getAllListeners());
        self::assertFalse($this->_eventManager->hasListeners(self::PRE_FOO));
        self::assertFalse($this->_eventManager->hasListeners(self::POST_FOO));
    }

    public function testAddEventListener(): void
    {
        $this->_eventManager->addEventListener(['preFoo', 'postFoo'], $this);
        self::assertTrue($this->_eventManager->hasListeners(self::PRE_FOO));
        self::assertTrue($this->_eventManager->hasListeners(self::POST_FOO));
        self::assertCount(1, $this->_eventManager->getListeners(self::PRE_FOO));
        self::assertCount(1, $this->_eventManager->getListeners(self::POST_FOO));
        self::assertCount(2, $this->_eventManager->getAllListeners());
        self::assertSame(['preFoo', 'postFoo'], array_keys($this->_eventManager->getAllListeners()));
    }

    public function testGetListenersDeprecation(): void
    {
        $this->_eventManager->addEventListener(['preFoo', 'postFoo'], $this);

        $this->expectDeprecationWithIdentifier('https://github.com/doctrine/event-manager/pull/50');
        self::assertCount(2, $this->_eventManager->getListeners());
    }

    public function testDispatchEvent(): void
    {
        $this->_eventManager->addEventListener(['preFoo', 'postFoo'], $this);
        $this->_eventManager->dispatchEvent(self::PRE_FOO);
        self::assertTrue($this->_preFooInvoked);
        self::assertFalse($this->_postFooInvoked);
    }

    public function testRemoveEventListener(): void
    {
        $this->_eventManager->addEventListener(['preBar'], $this);
        self::assertTrue($this->_eventManager->hasListeners(self::PRE_BAR));
        $this->_eventManager->removeEventListener(['preBar'], $this);
        self::assertFalse($this->_eventManager->hasListeners(self::PRE_BAR));
    }

    public function testAddEventSubscriber(): void
    {
        $eventSubscriber = new TestEventSubscriber();
        $this->_eventManager->addEventSubscriber($eventSubscriber);
        self::assertTrue($this->_eventManager->hasListeners(self::PRE_FOO));
        self::assertTrue($this->_eventManager->hasListeners(self::POST_FOO));
    }

    public function testRemoveEventSubscriber(): void
    {
        $eventSubscriber = new TestEventSubscriber();
        $this->_eventManager->addEventSubscriber($eventSubscriber);
        $this->_eventManager->removeEventSubscriber($eventSubscriber);
        self::assertFalse($this->_eventManager->hasListeners(self::PRE_FOO));
        self::assertFalse($this->_eventManager->hasListeners(self::POST_FOO));
    }

    public function testNoDispatchingForUnregisteredEvent(): void
    {
        $reflection = new ReflectionProperty(EventArgs::class, '_emptyEventArgsInstance');
        $reflection->setAccessible(true);
        $reflection->setValue(null, null);

        $this->_eventManager->dispatchEvent('unknown');

        self::assertNull($reflection->getValue(null));
    }

    public function testEmptyListenersListForUnregisteredEvent(): void
    {
        self::assertSame([], $this->_eventManager->getListeners('unknown'));
    }

    /* Listener methods */

    public function preFoo(EventArgs $e): void
    {
        $this->_preFooInvoked = true;
    }

    public function postFoo(EventArgs $e): void
    {
        $this->_postFooInvoked = true;
    }
}

class TestEventSubscriber implements EventSubscriber
{
    /**
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return ['preFoo', 'postFoo'];
    }
}
