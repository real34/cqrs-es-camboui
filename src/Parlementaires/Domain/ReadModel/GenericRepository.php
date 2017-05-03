<?php

namespace Parlementaires\Domain\ReadModel;

interface GenericRepository
{
    const PK_FIELD = 'id';

    public function save($record);
    public function get($id);
    public function findAll(): array;
    public function count(): int;
    public function exists($id): bool;
}