<?php

declare(strict_types=1);

namespace Doctrine\Common;

/** Provided for convenience, but consider using the individual interfaces directly. */
interface EventManagerInterface extends
    EventListenerIntrospector,
    EventListenerRegistry,
    EventSubscriberRegistry
{
}
