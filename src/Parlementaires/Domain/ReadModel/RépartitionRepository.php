<?php

namespace Parlementaires\Domain\ReadModel;

use Parlementaires\Domain\ValueObject\Bénéficiaire;

interface RépartitionRepository
{
    const PK_FIELD = 'id';

    public function save($record);
    public function get(Bénéficiaire $id);
    public function findAll(): array;
    public function count(): int;
    public function exists(Bénéficiaire $id): bool;
    public function truncate();
}