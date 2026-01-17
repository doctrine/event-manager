<?php

declare(strict_types=1);

namespace Doctrine\Common;

/**
 * Interface for introspecting event listeners.
 *
 * Provides methods to query registered listeners without modifying them.
 */
interface EventListenerIntrospector extends EventDispatcher
{
    /**
     * Gets the listeners of a specific event.
     *
     * @param string $event The name of the event.
     *
     * @return object[]
     */
    public function getListeners(string $event): array;

    /**
     * Gets all listeners keyed by event name.
     *
     * @return array<string, object[]>
     */
    public function getAllListeners(): array;

    /**
     * Checks whether an event has any registered listeners.
     */
    public function hasListeners(string $event): bool;
}
