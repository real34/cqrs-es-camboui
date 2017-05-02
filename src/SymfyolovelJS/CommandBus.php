<?php

namespace SymfyolovelJS;

/**
 * @see https://tactician.thephpleague.com/
 * @see http://simplebus.github.io/MessageBus/
 */
class CommandBus
{
    private $commandToHandlerMapping;

    private function __construct(array $commandToHandlerMapping)
    {
        $this->commandToHandlerMapping = $commandToHandlerMapping;
    }

    public static function createWithHandlers(array $commandToHandlerMapping) : CommandBus
    {
        return new static($commandToHandlerMapping);
    }

    public function handle($command)
    {
        if (!is_object($command)) {
            throw new \InvalidArgumentException('The Command must be an object');
        }

        $handler = $this->resolveHandlerByFQN(get_class($command));
        $handler($command);
    }

    private function resolveHandlerByFQN(string $fqn)
    {
        if (!array_key_exists($fqn, $this->commandToHandlerMapping)) {
            throw new \UnexpectedValueException(sprintf(
                'Unsupported command type "%s"',
                $fqn
            ));
        }

        return $this->commandToHandlerMapping[$fqn];
    }
}