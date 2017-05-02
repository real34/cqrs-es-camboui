<?php

namespace Parlementaires\Domain\Tests\ValueObject;

use Parlementaires\Domain\ValueObject\IdActeur;
use Parlementaires\Domain\ValueObject\ValeurIncorrecte;
use PHPUnit\Framework\TestCase;

class IdActeurTest extends TestCase
{
    public function test_PA607090_est_un_id_valide()
    {
        new IdActeur('PA607090');
        $this->assertTrue(true, 'No exception thrown');
    }

    public function test_la_valeur_ne_doit_pas_être_vide()
    {
        $this->expectException(ValeurIncorrecte::class);
        new IdActeur('');
    }

    public function test_NULL_est_un_id_incorrect()
    {
        $this->expectException(ValeurIncorrecte::class);
        new IdActeur('NULL');
    }
}
