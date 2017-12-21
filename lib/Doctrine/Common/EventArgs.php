<?php
namespace Doctrine\Common;

/**
 * EventArgs is the base class for classes containing event data.
 *
 * This class contains no event data. It is used by events that do not pass state
 * information to an event handler when an event is raised. The single empty EventArgs
 * instance can be obtained through {@link getEmptyInstance}.
 *
 * @link   www.doctrine-project.org
 * @since  2.0
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 */
class EventArgs
{
    /**
     * Single instance of EventArgs.
     *
     * @var EventArgs
     */
    private static $_emptyEventArgsInstance;

    /**
     * Gets the single, empty and immutable EventArgs instance.
     *
     * This instance will be used when events are dispatched without any parameter,
     * like this: EventManager::dispatchEvent('eventname');
     *
     * The benefit from this is that only one empty instance is instantiated and shared
     * (otherwise there would be instances for every dispatched in the abovementioned form).
     *
     * @see EventManager::dispatchEvent
     *
     * @link http://msdn.microsoft.com/en-us/library/system.eventargs.aspx
     *
     * @return EventArgs
     */
    public static function getEmptyInstance()
    {
        if ( ! self::$_emptyEventArgsInstance) {
            self::$_emptyEventArgsInstance = new EventArgs;
        }

        return self::$_emptyEventArgsInstance;
    }
}
