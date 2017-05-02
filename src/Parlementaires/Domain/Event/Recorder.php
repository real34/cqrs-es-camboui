<?php

namespace Parlementaires\Domain\Event;

interface Recorder
{
    public function record(DomainEvent $event);
}