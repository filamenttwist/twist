<?php

namespace Twist\Base;

use Illuminate\Support\Str;
use Obelaw\Trail\Facades\Trail;
use ReflectionClass;

/**
 * An abstract base for creating executable action classes.
 *
 * This class provides a standardized way to create self-contained, single-purpose
 * service classes. It includes a static `make()` method for easy execution and
 * built-in, automatic event tracking via the Obelaw Trail package.
 */
abstract class BaseAction
{
    /**
     * A flag to enable or disable event tracking for this action.
     *
     * @var bool
     */
    protected bool $trackable = true;

    /**
     * Manually override the event name for tracking.
     * If null, the name is generated automatically from the class name.
     *
     * @var string|null
     */
    protected ?string $trackableEvent = null;

    /**
     * The suffix to be removed from the class name when generating an event name.
     *
     * @var string
     */
    protected string $eventSuffix = 'Action';

    /**
     * Creates, resolves, and executes the action.
     *
     * This is the primary static entry point for all actions. It uses the
     * service container to instantiate the action, calls the tracking method,
     * and then executes the main `handle` method, passing all arguments to both.
     *
     * @param mixed ...$arguments The arguments to be passed to the action's handle method.
     * @return mixed The result of the action's handle method.
     */
    public static function make(mixed ...$arguments): mixed
    {
        $static = app(static::class);

        $static->track($arguments);

        return $static->handle(...$arguments);
    }

    /**
     * Tracks the execution of the action as an event.
     *
     * This method records the event using the Trail facade. It will not run
     * if tracking is disabled (`$trackable = false`) or if no event name can be determined.
     * By convention, it assumes the first argument passed to the action is the
     * array of changes to be recorded.
     *
     * @param array $arguments The arguments passed to the `make` method.
     * @return void
     */
    private function track(array $arguments): void
    {
        $eventName = $this->trackableEvent ?? $this->generateEventName();

        if (empty($eventName) || !$this->trackable) {
            return;
        }

        // By convention, the first argument is the array of changes.
        $changes = $arguments[0] ?? [];
        if (!is_array($changes)) {
            $changes = []; // Ensure it's always an array.
        }

        Trail::for()
            ->by(auth()->user())
            ->event($eventName)
            ->changes($changes)
            ->snapshot([])
            ->save();
    }

    /**
     * Generates a dot-separated event name from the action's class name.
     *
     * Example: `UpdateOrderStatusAction` becomes `update.order.status`.
     *
     * @return string
     */
    private function generateEventName(): string
    {
        // Get the short name of the class (e.g., "UpdateOrderStatusAction")
        $className = (new ReflectionClass($this))->getShortName();

        // Remove the "Action" suffix
        $className = Str::beforeLast($className, $this->eventSuffix);

        // Convert to snake case with dot separators (e.g., "update.order.status")
        return Str::of($className)->snake('.')->toString();
    }
}
