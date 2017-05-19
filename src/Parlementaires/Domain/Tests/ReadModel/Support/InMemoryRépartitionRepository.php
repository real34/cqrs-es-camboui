<?php

namespace Parlementaires\Domain\Tests\ReadModel\Support;

use Parlementaires\Domain\ReadModel\GenericRepository;
use Parlementaires\Domain\ReadModel\RépartitionRepository;
use Parlementaires\Domain\ValueObject\Bénéficiaire;

class InMemoryRépartitionRepository implements RépartitionRepository
{
    protected $db = [];
    private $repository;

    public function __construct()
    {
        $this->repository = new InMemoryGenericRepository();
    }

    public function save($record)
    {
        $record[GenericRepository::PK_FIELD] = $this->idOf($record[static::PK_FIELD]);
        $this->repository->save($record);
    }

    public function get(Bénéficiaire $id)
    {
        return $this->withPublicId(
            $this->repository->get($this->idOf($id))
        );
    }

    public function findAll(): array
    {
        return array_map(
            [$this, 'withPublicId'],
            $this->repository->findAll()
        );
    }

    public function count(): int
    {
        return $this->repository->count();
    }

    public function exists(Bénéficiaire $id): bool
    {
        return $this->repository->exists(
            $this->idOf($id)
        );
    }

    public function truncate()
    {
        $this->repository->truncate();
    }

    private function withPublicId($record)
    {
        $record[static::PK_FIELD] = $this->idFrom($record[GenericRepository::PK_FIELD]);
        return $record;
    }

    private function idOf(Bénéficiaire $bénéficiaire)
    {
        return serialize([
            'nom' => $bénéficiaire->getNom(),
            'adresse' => $bénéficiaire->getAdresse()
        ]);
    }

    private function idFrom(string $id) : Bénéficiaire
    {
        $metadata = unserialize($id);
        return new Bénéficiaire(
            $metadata['nom'],
            $metadata['adresse']
        );
    }

    public function findTop(int $limit): array
    {
        return $this->findWithSort(function($a, $b) {
            return $b['total_en_euros'] <=> $a['total_en_euros'];
        }, $limit);
    }

    private function findWithSort(callable $sort, int $limit): array
    {
        $all = $this->findAll();
        usort($all, $sort);
        return array_slice($all, 0, $limit);
    }
}