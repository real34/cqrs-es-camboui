<?php

namespace Parlementaires\Domain\Tests\ReadModel;

use Parlementaires\Domain\Event\SubventionAttribuée;
use Parlementaires\Domain\ReadModel\RépartitionParBénéficiaireProjector;
use Parlementaires\Domain\Tests\ReadModel\Support\InMemoryRépartitionRepository;
use Parlementaires\Domain\ValueObject\Bénéficiaire;
use Parlementaires\Domain\ValueObject\IdActeur;
use Parlementaires\Domain\ValueObject\IdProgramme;
use Parlementaires\Domain\ValueObject\Monnaie;
use PHPUnit\Framework\TestCase;

class RépartitionParBénéficiaireProjectorTest extends TestCase
{
    public function testLorsqueLaPremièreSubventionEstAttribuéeUneEntréeEstCréée()
    {
        $repository = new InMemoryRépartitionRepository();

        $bénéficiaire = new Bénéficiaire('AFUP', "Rue de l'éléphant\n13370 Serveur");
        (new RépartitionParBénéficiaireProjector($repository))
            ->handleSubventionAttribuée(
                $this->makeSubventionAttribuée($bénéficiaire, 500)
            );

        $this->assertEquals(1, $repository->count());
    }

    public function testQuandUneSeuleSubventionAÉtéAttribuéeLeTotalEstCeluiDeLaSubvention()
    {
        $repository = new InMemoryRépartitionRepository();

        $bénéficiaire = new Bénéficiaire('AFUP', "Rue de l'éléphant\n13370 Serveur");
        (new RépartitionParBénéficiaireProjector($repository))
            ->handleSubventionAttribuée(
                $this->makeSubventionAttribuée($bénéficiaire, 500)
            );
        $actual = $repository->get($bénéficiaire);

        $this->assertEquals(500, $actual['total_en_euros']);
    }

    public function testQuandDeuxSubventionsOntÉtéAttribuéesPourDeuxBénéficiairesDifférentsLeTotalEstCeluiDeLaSubventionRespectivePourChacun()
    {
        $repository = new InMemoryRépartitionRepository();

        $afup = new Bénéficiaire('AFUP', "Rue de l'éléphant\n13370 Serveur");
        $mairie = new Bénéficiaire('Mairie de Paris', '');

        $SUT = new RépartitionParBénéficiaireProjector($repository);
        array_map([$SUT, 'handleSubventionAttribuée'], [
            $this->makeSubventionAttribuée($afup, 500),
            $this->makeSubventionAttribuée($mairie, 4242)
        ]);

        $actual = array_map(function($row) {
            return ['id' => $row['id'], 'total_en_euros' => $row['total_en_euros']];
        }, $repository->findAll());

        $expected = [
            ['id' => $afup, 'total_en_euros' => 500],
            ['id' => $mairie, 'total_en_euros' => 4242],
        ];
        $this->assertEquals($expected, $actual);
    }

    public function testQuandDeuxSubventionsOntÉtéAttribuéesParUnMêmeActeurLeTotalEstLaSommeDesMontants()
    {
        $repository = new InMemoryRépartitionRepository();

        $afup = new Bénéficiaire('AFUP', "Rue de l'éléphant\n13370 Serveur");
        $mairie = new Bénéficiaire('Mairie de Paris', '');

        $SUT = new RépartitionParBénéficiaireProjector($repository);
        array_map([$SUT, 'handleSubventionAttribuée'], [
            $this->makeSubventionAttribuée($afup, 500),
            $this->makeSubventionAttribuée($afup, 2500),
            $this->makeSubventionAttribuée($mairie, 4242)
        ]);

        $actual = $repository->get($afup);

        $this->assertEquals(500 + 2500, $actual['total_en_euros']);
    }

    public function testQuandDeuxSubventionsOntÉtéAttribuéesParUnMêmeActeurLeNombreDeSubventionsEstDe2()
    {
        $repository = new InMemoryRépartitionRepository();

        $afup = new Bénéficiaire('AFUP', "Rue de l'éléphant\n13370 Serveur");
        $mairie = new Bénéficiaire('Mairie de Paris', '');

        $SUT = new RépartitionParBénéficiaireProjector($repository);
        array_map([$SUT, 'handleSubventionAttribuée'], [
            $this->makeSubventionAttribuée($afup, 500),
            $this->makeSubventionAttribuée($afup, 2500),
            $this->makeSubventionAttribuée($mairie, 4242)
        ]);

        $actual = $repository->get($afup);

        $this->assertEquals(2, $actual['nombre_de_subventions']);
    }

    private function makeSubventionAttribuée(Bénéficiaire $bénéficiaire, int $montant)
    {
        return new SubventionAttribuée(
            new IdActeur('PA123'),
            $bénéficiaire,
            Monnaie::EUR($montant),
            new IdProgramme('167-02'),
            'Une description'
        );
    }
}
