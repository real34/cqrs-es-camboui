<?php

namespace Parlementaires\Domain\ReadModel;

use Parlementaires\Domain\Event\SubventionAttribuée;

class RépartitionParBénéficiaireProjector
{
    private $répartitionRepository;

    public function __construct(RépartitionRepository $répartitionRepository)
    {
        $this->répartitionRepository = $répartitionRepository;
    }

    public function handleSubventionAttribuée(SubventionAttribuée $event)
    {
        $id = $event->getBénéficiaire();
        $montant = $event->getMontant()->getMontant();

        if ($this->répartitionRepository->exists($id)) {
            $répartition = $this->répartitionRepository->get($id);
            $répartition['total_en_euros'] += $montant;
            $répartition['nombre_de_subventions']++;
        } else {
            $répartition = [
                GenericRepository::PK_FIELD => $id,
                'total_en_euros' => $montant,
                'nombre_de_subventions' => 1
            ];
        }

        $this->répartitionRepository->save($répartition);
    }

}