<?php

namespace SymfyolovelJS;
use Parlementaires\Domain\Event\DomainEvent;

/**
 * Same thing than the Command Bus in this very simple
 * and naive implementation, the only difference being that
 * there could be multiple handlers per event
 *
 * @see https://tactician.thephpleague.com/
 * @see http://simplebus.github.io/MessageBus/
 */
class EventBus
{
    private $eventToHandlersMapping;

    private function __construct(array $eventToHandlersMapping)
    {
        $this->eventToHandlersMapping = $eventToHandlersMapping;
    }

    public static function createWithHandlers(array $eventToHandlersMapping) : EventBus
    {
        return new static($eventToHandlersMapping);
    }

    public function handle(DomainEvent $event)
    {
        $handlers = $this->resolveHandlersByFQN(get_class($event));
        foreach ($handlers as $handler) {
            $handler($event);
        }
    }

    private function resolveHandlersByFQN(string $fqn): array
    {
        return $this->eventToHandlersMapping[$fqn] ?? [];
    }
}