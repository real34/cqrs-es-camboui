<?php

namespace Parlementaires\Domain\Tests\CommandHandler;

use Parlementaires\Domain\Command\AttribuerSubvention;
use Parlementaires\Domain\Event\Recorder;
use Parlementaires\Domain\Event\SubventionAttribuée;
use Parlementaires\Domain\ValueObject\Bénéficiaire;
use Parlementaires\Domain\ValueObject\IdActeur;
use Parlementaires\Domain\ValueObject\IdProgramme;
use Parlementaires\Domain\ValueObject\Monnaie;
use PHPUnit\Framework\TestCase;

class RéserveParlementaire extends TestCase
{
    public function testUneAttributionDeSubventionEstToujoursAcceptée()
    {
        $command = new AttribuerSubvention(
            new IdActeur(),
            new Bénéficiaire(),
            new Monnaie(),
            new IdProgramme(),
            'Cette pauvre association fait des choses bien pour notre communauté'
        );

        $eventRecorder = $this->prophesize(Recorder::class);
        $SUT = new \Parlementaires\Domain\CommandHandler\RéserveParlementaire($eventRecorder->reveal());

        $SUT->handleAttribuerSubvention($command);

        $expectedEvent = new SubventionAttribuée(
            new IdActeur(),
            new Bénéficiaire(),
            new Monnaie(),
            new IdProgramme(),
            'Cette pauvre association fait des choses bien pour notre communauté'
        );
        $eventRecorder->record($expectedEvent)->shouldBeCalledTimes(1);
    }
}