<?php

namespace Parlementaires\Domain\ValueObject;

use Parlementaires\Domain\Serializable;

class IdProgramme implements Serializable
{
    private $value;

    public function __construct(string $value)
    {
        $this->guardAgainstInvalidValue($value);
        $this->value = $value;
    }

    private function guardAgainstInvalidValue($value)
    {
        // TODO Homogénéïser ou échouer en cas de données incorrectes
        // Valides: "313-03", "203-15"
        // Invalides: "101- 04" (espace)
    }

    public function getValue() : string
    {
        return $this->value;
    }

    public static function deserialize($value)
    {
        return new static($value);
    }

    public function serialize()
    {
        return $this->getValue();
    }
}