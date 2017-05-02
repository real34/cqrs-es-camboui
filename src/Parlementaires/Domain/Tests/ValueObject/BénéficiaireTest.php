<?php

namespace Parlementaires\Domain\Tests\ValueObject;

use Parlementaires\Domain\ValueObject\Bénéficiaire;
use Parlementaires\Domain\ValueObject\ValeurIncorrecte;
use PHPUnit\Framework\TestCase;

class BénéficiaireTest extends TestCase
{
    private $aNom = 'YOLO Asso';
    private $anAddresse = 'Paris';

    public function testLeNomDuBénéficiaireNePeutPasÊtreVide()
    {
        $this->expectException(ValeurIncorrecte::class);
        new Bénéficiaire('', $this->anAddresse);
    }

    public function testUneAddresseContenantUnRetourChariotADeuxLignes()
    {
        $SUT = new Bénéficiaire($this->aNom, "Étincelle Coworking\n31000 Toulouse");
        $this->assertCount(2, $SUT->getLignesAdresse());
    }
}
