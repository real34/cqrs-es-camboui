<?php

namespace Parlementaires\Domain\ValueObject;

// @see https://github.com/moneyphp/money to go further
use Parlementaires\Domain\Serializable;

class Monnaie implements Serializable
{
    private $montant;
    private $devise;

    private function __construct(int $montant, Devise $devise)
    {
        $this->montant = $montant;
        $this->devise = $devise;
    }

    public static function EUR(int $montant) : Monnaie
    {
        return new static($montant, new Devise('EUR'));
    }

    public function getMontant() : int
    {
        return $this->montant;
    }

    public function getDevise() : Devise
    {
        return $this->devise;
    }

    public static function deserialize($data)
    {
        return new static(
            $data['montant'],
            new Devise($data['devise'])
        );
    }

    public function serialize()
    {
        return [
            'montant' => $this->getMontant(),
            'devise' => $this->getDevise()->getCode(),
        ];
    }
}