<?php

declare(strict_types=1);

namespace Doctrine\Common;

/**
 * Interface for registering and unregistering event listeners.
 */
interface EventListenerRegistry extends EventDispatcher
{
    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param string|string[] $events   The event(s) to listen on.
     * @param object          $listener The listener object.
     */
    public function addEventListener(string|array $events, object $listener): void;

    /**
     * Removes an event listener from the specified events.
     *
     * @param string|string[] $events
     */
    public function removeEventListener(string|array $events, object $listener): void;
}
