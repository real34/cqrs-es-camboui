<?php

namespace Parlementaires\Domain\Event;

use Parlementaires\Domain\ValueObject\Bénéficiaire;
use Parlementaires\Domain\ValueObject\IdActeur;
use Parlementaires\Domain\ValueObject\IdProgramme;
use Parlementaires\Domain\ValueObject\Monnaie;

class SubventionAttribuée implements DomainEvent
{
    private $idActeur;
    private $bénéficiaire;
    private $montant;
    private $idProgramme;
    private $descriptif;

    public function __construct(IdActeur $idActeur, Bénéficiaire $bénéficiaire, Monnaie $montant, IdProgramme $idProgramme, string $descriptif)
    {
        $this->idActeur = $idActeur;
        $this->bénéficiaire = $bénéficiaire;
        $this->montant = $montant;
        $this->idProgramme = $idProgramme;
        $this->descriptif = $descriptif;
    }

    public function getIdActeur() : IdActeur
    {
        return $this->idActeur;
    }

    public function getBénéficiaire() : Bénéficiaire
    {
        return $this->bénéficiaire;
    }

    public function getMontant() : Monnaie
    {
        return $this->montant;
    }

    public function getIdProgramme() : IdProgramme
    {
        return $this->idProgramme;
    }

    public function getDescriptif() : string
    {
        return $this->descriptif;
    }
}