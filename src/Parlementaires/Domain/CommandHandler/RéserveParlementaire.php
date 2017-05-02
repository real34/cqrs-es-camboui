<?php

namespace Parlementaires\Domain\CommandHandler;

use Parlementaires\Domain\Command\AttribuerSubvention;
use Parlementaires\Domain\Event\Recorder;
use Parlementaires\Domain\Event\SubventionAttribuée;

class RéserveParlementaire
{
    private $eventRecorder;

    public function __construct(Recorder $eventRecorder)
    {
        $this->eventRecorder = $eventRecorder;
    }

    public function handleAttribuerSubvention(AttribuerSubvention $command)
    {
//        TODO Créer un aggrégat avec la logique qui va bien
//        $acteur = $this->acteurRepository->load($command->getIdActeur());
//        $acteur->attribueSubvention();
//        $this->acteurRepository->save($acteur);

        $event = new SubventionAttribuée(
            $command->getIdActeur(),
            $command->getBénéficiaire(),
            $command->getMontant(),
            $command->getIdProgramme(),
            $command->getDescriptif()
        );
        $this->eventRecorder->record($event);
    }
}