<?php

namespace Parlementaires\Infrastructure;

use Parlementaires\Domain\Event\DomainEvent;

final class DomainMessage
{
    private $payload;
    private $recordedOn;
    private $metadata;

    private function __construct(DomainEvent $payload, array $metadata, \DateTimeInterface $recordedOn)
    {
        $this->payload = $payload;
        $this->recordedOn = $recordedOn;
        $this->metadata = $metadata;
    }

    public static function recordNow(DomainEvent $payload, array $metadata = []) : DomainMessage
    {
        return new static($payload, $metadata, new \DateTimeImmutable());
    }

    public function getPayload() : DomainEvent
    {
        return $this->payload;
    }

    public function getRecordedOn() : \DateTimeInterface
    {
        return $this->recordedOn;
    }

    public function getMetadata() : array
    {
        return $this->metadata;
    }

    public function getType()
    {
        return get_class($this->getPayload());
    }
}