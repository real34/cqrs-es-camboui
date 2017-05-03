<?php

namespace Parlementaires\Infrastructure;

interface EventStore
{
    public function append(DomainMessage $message);
    public function allEvents(): \Iterator;
}