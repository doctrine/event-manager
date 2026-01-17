<?php

declare(strict_types=1);

namespace Doctrine\Common;

/**
 * Interface for registering and unregistering event subscribers.
 */
interface EventSubscriberRegistry extends EventDispatcher
{
    /**
     * Adds an EventSubscriber.
     *
     * The subscriber is asked for all the events it is interested in and added
     * as a listener for these events.
     */
    public function addEventSubscriber(EventSubscriber $subscriber): void;

    /**
     * Removes an EventSubscriber.
     *
     * The subscriber is asked for all the events it is interested in and removed
     * as a listener for these events.
     */
    public function removeEventSubscriber(EventSubscriber $subscriber): void;
}
