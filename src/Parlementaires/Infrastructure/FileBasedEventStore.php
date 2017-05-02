<?php

namespace Parlementaires\Infrastructure;

class FileBasedEventStore implements EventStore
{
    private $dirname;

    public function __construct($dirname)
    {
        $this->dirname = $dirname;
    }

    public function append(DomainMessage $message)
    {
        $filePath = $this->buildFilePath($message);
        var_dump('[DEBUG][EVENT CREATED] ' . $filePath);
        file_put_contents($filePath, json_encode([
            'type' => $message->getType(),
            'payload' => $message->getPayload()->serialize(),
            'metadata' => $message->getMetadata(),
            'recorded_on' => $message->getRecordedOn()
        ]));
    }

    private function buildFilePath(DomainMessage $message)
    {
        $id = count(glob($this->dirname . '/*.json')) + 1;
        return sprintf(
            '%s/%s_%s.json',
            $this->dirname,
            str_pad($id, 10, '0', STR_PAD_LEFT),
            array_pop(explode('\\', $message->getType()))
        );
    }
}