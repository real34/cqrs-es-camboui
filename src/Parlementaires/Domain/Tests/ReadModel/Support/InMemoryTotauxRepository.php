<?php

namespace Parlementaires\Domain\Tests\ReadModel\Support;

use Parlementaires\Domain\ReadModel\TotauxRepository;

class InMemoryTotauxRepository extends InMemoryGenericRepository implements TotauxRepository
{
    public function findTop(int $limit = 10): array
    {
        return $this->findWithSort(function($a, $b) {
            return $b['total_en_euros'] <=> $a['total_en_euros'];
        }, $limit);
    }

    public function findFlop(int $limit = 10): array
    {
        return $this->findWithSort(function($a, $b) {
            return $a['total_en_euros'] <=> $b['total_en_euros'];
        }, $limit);
    }

    private function findWithSort(callable $sort, int $limit): array
    {
        $all = $this->findAll();
        usort($all, $sort);
        return array_slice($all, 0, $limit);
    }
}