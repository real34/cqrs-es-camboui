<?php

namespace Parlementaires\Infrastructure;

use Parlementaires\Domain\Event\DomainEvent;
use Parlementaires\Domain\Event\Recorder;

class DomainEventsRecorder implements Recorder
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function record(DomainEvent $event)
    {
        $domainMessage = DomainMessage::recordNow($event, [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'source' => $_ENV['PARL_SOURCE'] ?? ''
        ]);
        $this->eventStore->append($domainMessage);
    }
}