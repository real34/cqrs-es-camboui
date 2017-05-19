<?php

namespace Parlementaires\Domain\Tests\ReadModel;

use Parlementaires\Domain\Event\SubventionAttribuée;
use Parlementaires\Domain\ReadModel\TotauxParActeurProjector;
use Parlementaires\Domain\Tests\ReadModel\Support\InMemoryTotauxRepository;
use Parlementaires\Domain\ValueObject\Bénéficiaire;
use Parlementaires\Domain\ValueObject\IdActeur;
use Parlementaires\Domain\ValueObject\IdProgramme;
use Parlementaires\Domain\ValueObject\Monnaie;
use PHPUnit\Framework\TestCase;

class TotauxParActeurProjectorTest extends TestCase
{
    public function testLorsqueLaPremièreSubventionEstAttribuéeUneEntréeEstCréée()
    {
        $repository = new InMemoryTotauxRepository();

        (new TotauxParActeurProjector($repository))
            ->handleSubventionAttribuée(
                $this->makeSubventionAttribuée('PA607090', 500)
            );

        $this->assertEquals(1, $repository->count());
    }

    public function testQuandUneSeuleSubventionAÉtéAttribuéeLeTotalEstCeluiDeLaSubvention()
    {
        $repository = new InMemoryTotauxRepository();

        (new TotauxParActeurProjector($repository))
            ->handleSubventionAttribuée(
                $this->makeSubventionAttribuée('PA607090', 500)
            );
        $actual = $repository->get('PA607090');

        $this->assertEquals(500, $actual['total_en_euros']);
    }

    public function testQuandDeuxSubventionsOntÉtéAttribuéesParDeuxActeursDifférentsLeTotalEstCeluiDeLaSubventionRespectivePourChacun()
    {
        $repository = new InMemoryTotauxRepository();

        $SUT = new TotauxParActeurProjector($repository);
        array_map([$SUT, 'handleSubventionAttribuée'], [
            $this->makeSubventionAttribuée('PA607090', 500),
            $this->makeSubventionAttribuée('PA007', 4242)
        ]);

        $actual = array_map(function($row) {
            return ['id' => $row['id'], 'total_en_euros' => $row['total_en_euros']];
        }, $repository->findAll());

        $expected = [
            ['id' => 'PA607090', 'total_en_euros' => 500],
            ['id' => 'PA007', 'total_en_euros' => 4242],
        ];
        $this->assertEquals($expected, $actual);
    }

    public function testQuandDeuxSubventionsOntÉtéAttribuéesParUnMêmeActeurLeTotalEstLaSommeDesMontants()
    {
        $repository = new InMemoryTotauxRepository();

        $SUT = new TotauxParActeurProjector($repository);
        array_map([$SUT, 'handleSubventionAttribuée'], [
            $this->makeSubventionAttribuée('PA607090', 500),
            $this->makeSubventionAttribuée('PA607090', 2500),
            $this->makeSubventionAttribuée('PA007', 4242)
        ]);

        $actual = $repository->get('PA607090');

        $this->assertEquals(500 + 2500, $actual['total_en_euros']);
    }

    public function testQuandDeuxSubventionsOntÉtéAttribuéesParUnMêmeActeurLeNombreDeSubventionsEstDe2()
    {
        $repository = new InMemoryTotauxRepository();

        $SUT = new TotauxParActeurProjector($repository);
        array_map([$SUT, 'handleSubventionAttribuée'], [
            $this->makeSubventionAttribuée('PA607090', 500),
            $this->makeSubventionAttribuée('PA607090', 2500),
            $this->makeSubventionAttribuée('PA007', 4242)
        ]);

        $actual = $repository->get('PA607090');

        $this->assertEquals(2, $actual['nombre_de_subventions']);
    }

    private function makeSubventionAttribuée(string $idActeur, int $montant)
    {
        return new SubventionAttribuée(
            new IdActeur($idActeur),
            new Bénéficiaire('Entraide et partage', 'Paris'),
            Monnaie::EUR($montant),
            new IdProgramme('167-02'),
            'Une description'
        );
    }
}
