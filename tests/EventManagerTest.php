<?php

declare(strict_types=1);

namespace Doctrine\Tests\Common;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventManager;
use Doctrine\Common\EventSubscriber;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

use function array_keys;

class EventManagerTest extends TestCase
{
    /* Some pseudo events */
    private const PRE_FOO  = 'preFoo';
    private const POST_FOO = 'postFoo';
    private const PRE_BAR  = 'preBar';

    private bool $preFooInvoked  = false;
    private bool $postFooInvoked = false;
    private EventManager $eventManager;

    protected function setUp(): void
    {
        $this->eventManager   = new EventManager();
        $this->preFooInvoked  = false;
        $this->postFooInvoked = false;
    }

    public function testInitialState(): void
    {
        self::assertEquals([], $this->eventManager->getAllListeners());
        self::assertFalse($this->eventManager->hasListeners(self::PRE_FOO));
        self::assertFalse($this->eventManager->hasListeners(self::POST_FOO));
    }

    public function testAddEventListener(): void
    {
        $this->eventManager->addEventListener(['preFoo', 'postFoo'], $this);
        self::assertTrue($this->eventManager->hasListeners(self::PRE_FOO));
        self::assertTrue($this->eventManager->hasListeners(self::POST_FOO));
        self::assertCount(1, $this->eventManager->getListeners(self::PRE_FOO));
        self::assertCount(1, $this->eventManager->getListeners(self::POST_FOO));
        self::assertCount(2, $this->eventManager->getAllListeners());
        self::assertSame(['preFoo', 'postFoo'], array_keys($this->eventManager->getAllListeners()));
    }

    public function testDispatchEvent(): void
    {
        $this->eventManager->addEventListener(['preFoo', 'postFoo'], $this);
        $this->eventManager->dispatchEvent(self::PRE_FOO);
        self::assertTrue($this->preFooInvoked);
        self::assertFalse($this->postFooInvoked);
    }

    public function testRemoveEventListener(): void
    {
        $this->eventManager->addEventListener(['preBar'], $this);
        self::assertTrue($this->eventManager->hasListeners(self::PRE_BAR));
        $this->eventManager->removeEventListener(['preBar'], $this);
        self::assertFalse($this->eventManager->hasListeners(self::PRE_BAR));
    }

    public function testAddEventSubscriber(): void
    {
        $eventSubscriber = new TestEventSubscriber();
        $this->eventManager->addEventSubscriber($eventSubscriber);
        self::assertTrue($this->eventManager->hasListeners(self::PRE_FOO));
        self::assertTrue($this->eventManager->hasListeners(self::POST_FOO));
    }

    public function testRemoveEventSubscriber(): void
    {
        $eventSubscriber = new TestEventSubscriber();
        $this->eventManager->addEventSubscriber($eventSubscriber);
        $this->eventManager->removeEventSubscriber($eventSubscriber);
        self::assertFalse($this->eventManager->hasListeners(self::PRE_FOO));
        self::assertFalse($this->eventManager->hasListeners(self::POST_FOO));
    }

    public function testNoDispatchingForUnregisteredEvent(): void
    {
        $reflection = new ReflectionProperty(EventArgs::class, 'emptyEventArgsInstance');
        $reflection->setValue(null, null);

        $this->eventManager->dispatchEvent('unknown');

        self::assertNull($reflection->getValue(null));
    }

    public function testEmptyListenersListForUnregisteredEvent(): void
    {
        self::assertSame([], $this->eventManager->getListeners('unknown'));
    }

    /* Listener methods */

    public function preFoo(EventArgs $e): void
    {
        $this->preFooInvoked = true;
    }

    public function postFoo(EventArgs $e): void
    {
        $this->postFooInvoked = true;
    }
}

class TestEventSubscriber implements EventSubscriber
{
    /** @return string[] */
    public function getSubscribedEvents(): array
    {
        return ['preFoo', 'postFoo'];
    }
}
