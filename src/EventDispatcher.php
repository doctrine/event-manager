<?php

declare(strict_types=1);

namespace Doctrine\Common;

/**
 * Interface for event dispatching.
 *
 * This minimal interface is suitable for type-hinting in code that only needs
 * to dispatch events without configuring listeners.
 */
interface EventDispatcher
{
    /**
     * Dispatches an event to all registered listeners.
     *
     * @param string         $eventName The name of the event to dispatch. The name of the event is
     *                                  the name of the method that is invoked on listeners.
     * @param EventArgs|null $eventArgs The event arguments to pass to the event handlers/listeners.
     *                                  If not supplied, the single empty EventArgs instance is used.
     */
    public function dispatchEvent(string $eventName, EventArgs|null $eventArgs = null): void;
}
