<?php

namespace Parlementaires\Domain\ReadModel;

use Parlementaires\Domain\Event\SubventionAttribuée;

class TotauxParActeurProjector
{
    private $totauxRepository;

    public function __construct(TotauxRepository $totauxRepository)
    {
        $this->totauxRepository = $totauxRepository;
    }

    public function handleSubventionAttribuée(SubventionAttribuée $event)
    {
        $id = $event->getIdActeur()->getValue();
        $montant = $event->getMontant()->getMontant();

        if ($this->totauxRepository->exists($id)) {
            $total = $this->totauxRepository->get($id);
            $total['total_en_euros'] += $montant;
        } else {
            $total = [
                GenericRepository::PK_FIELD => $id,
                'total_en_euros' => $montant
            ];
        }

        $this->totauxRepository->save($total);
    }
}