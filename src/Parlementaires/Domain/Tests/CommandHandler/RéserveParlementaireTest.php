<?php

namespace Parlementaires\Domain\Tests\CommandHandler;

use Parlementaires\Domain\Command\AttribuerSubvention;
use Parlementaires\Domain\CommandHandler\RéserveParlementaire;
use Parlementaires\Domain\Event\Recorder;
use Parlementaires\Domain\Event\SubventionAttribuée;
use Parlementaires\Domain\ValueObject\Bénéficiaire;
use Parlementaires\Domain\ValueObject\IdActeur;
use Parlementaires\Domain\ValueObject\IdProgramme;
use Parlementaires\Domain\ValueObject\Monnaie;
use PHPUnit\Framework\TestCase;

class RéserveParlementaireTest extends TestCase
{
    public function testUneAttributionDeSubventionEstToujoursAcceptée()
    {
        $anIdActeur = new IdActeur('PA607090');
        $anidProgramme = new IdProgramme('167-02');
        $aDescriptif = 'Cette pauvre association fait des choses bien pour notre communauté';
        $aBénéficiaire = new Bénéficiaire('Entraide et partage', 'Paris');
        $aMontant = Monnaie::EUR(500);

        $command = new AttribuerSubvention(
            $anIdActeur,
            $aBénéficiaire,
            $aMontant,
            $anidProgramme,
            $aDescriptif
        );

        $eventRecorder = $this->prophesize(Recorder::class);
        $SUT = new RéserveParlementaire($eventRecorder->reveal());

        $SUT->handleAttribuerSubvention($command);

        $expectedEvent = new SubventionAttribuée(
            $anIdActeur,
            $aBénéficiaire,
            $aMontant,
            $anidProgramme,
            $aDescriptif
        );
        $eventRecorder->record($expectedEvent)->shouldBeCalledTimes(1);
    }
}