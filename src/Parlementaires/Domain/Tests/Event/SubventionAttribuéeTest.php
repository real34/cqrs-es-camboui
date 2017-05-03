<?php

namespace Parlementaires\Domain\Tests\Event;

use Parlementaires\Domain\Event\SubventionAttribuée;
use Parlementaires\Domain\ValueObject\Bénéficiaire;
use Parlementaires\Domain\ValueObject\IdActeur;
use Parlementaires\Domain\ValueObject\IdProgramme;
use Parlementaires\Domain\ValueObject\Monnaie;
use PHPUnit\Framework\TestCase;

class SubventionAttribuéeTest extends TestCase
{
    // TODO Généraliser ce test pour tout DomainEvent de manière à s'assurer
    // que cette propriété est toujours vraie
    public function testLaSérialisationEtDésérialisationConserveLesDonnées()
    {
        $event = new SubventionAttribuée(
            new IdActeur('PA607090'),
            new Bénéficiaire('Entraide et partage', 'Paris'),
            Monnaie::EUR(500),
            new IdProgramme('167-02'),
            'Cette pauvre association fait des choses bien pour notre communauté'
        );

        $actual = SubventionAttribuée::deserialize(
            $event->serialize()
        );

        $this->assertEquals($event, $actual);
    }
}
