<?php

declare(strict_types=1);

namespace Doctrine\Common;

use Override;

use function spl_object_id;

/**
 * The EventManager is the central point of Doctrine's event listener system.
 * Listeners are registered on the manager and events are dispatched through the
 * manager.
 */
class EventManager implements EventManagerInterface
{
    /**
     * Map of registered listeners.
     * <event> => <listeners>
     *
     * @var array<string, object[]>
     */
    private array $listeners = [];

    #[Override]
    public function dispatchEvent(string $eventName, EventArgs|null $eventArgs = null): void
    {
        if (! isset($this->listeners[$eventName])) {
            return;
        }

        $eventArgs ??= EventArgs::getEmptyInstance();

        foreach ($this->listeners[$eventName] as $listener) {
            $listener->$eventName($eventArgs);
        }
    }

    #[Override]
    public function getListeners(string $event): array
    {
        return $this->listeners[$event] ?? [];
    }

    #[Override]
    public function getAllListeners(): array
    {
        return $this->listeners;
    }

    #[Override]
    public function hasListeners(string $event): bool
    {
        return ! empty($this->listeners[$event]);
    }

    #[Override]
    public function addEventListener(string|array $events, object $listener): void
    {
        // Picks the hash code related to that listener
        $oid = spl_object_id($listener);

        foreach ((array) $events as $event) {
            // Overrides listener if a previous one was associated already
            // Prevents duplicate listeners on same event (same instance only)
            $this->listeners[$event][$oid] = $listener;
        }
    }

    #[Override]
    public function removeEventListener(string|array $events, object $listener): void
    {
        // Picks the hash code related to that listener
        $oid = spl_object_id($listener);

        foreach ((array) $events as $event) {
            unset($this->listeners[$event][$oid]);
        }
    }

    #[Override]
    public function addEventSubscriber(EventSubscriber $subscriber): void
    {
        $this->addEventListener($subscriber->getSubscribedEvents(), $subscriber);
    }

    #[Override]
    public function removeEventSubscriber(EventSubscriber $subscriber): void
    {
        $this->removeEventListener($subscriber->getSubscribedEvents(), $subscriber);
    }
}
