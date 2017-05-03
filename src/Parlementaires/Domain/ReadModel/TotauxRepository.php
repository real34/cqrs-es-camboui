<?php

namespace Parlementaires\Domain\ReadModel;

interface TotauxRepository extends GenericRepository
{
    public function findTop(int $limit = 10): array;
    public function findFlop(int $limit = 10): array;
}