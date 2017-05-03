<?php

namespace Parlementaires\Domain\Tests\ReadModel\Support;

use Parlementaires\Domain\ReadModel\GenericRepository;

class InMemoryGenericRepository implements GenericRepository
{
    private $db = [];

    public function save($record)
    {
        $id = $record[static::PK_FIELD];
        $this->db[$id] = $record;
    }

    public function get($id)
    {
        if (!$this->exists($id)) {
            throw new \OutOfRangeException(sprintf(
                'No record found with id "%s"',
                $id
            ));
        }
        return $this->db[$id];
    }

    public function findAll(): array
    {
        return array_values($this->db);
    }

    public function count(): int
    {
        return count($this->db);
    }

    public function exists($id): bool
    {
        return array_key_exists($id, $this->db);
    }

    public function truncate()
    {
        $this->db = [];
    }
}