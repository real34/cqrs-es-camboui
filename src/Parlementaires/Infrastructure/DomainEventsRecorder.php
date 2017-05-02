<?php

namespace Parlementaires\Infrastructure;

use Parlementaires\Domain\Event\DomainEvent;
use Parlementaires\Domain\Event\Recorder;

class DomainEventsRecorder implements Recorder
{
    public function record(DomainEvent $event)
    {
        // TODO Actually persist events
        var_dump($event);
    }
}