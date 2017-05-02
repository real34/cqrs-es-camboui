<?php

namespace Parlementaires\Domain\ValueObject;

use Parlementaires\Domain\Serializable;

class IdActeur implements Serializable
{
    private $value;

    public function __construct(string $value)
    {
        $this->guardAgainstInvalidValue($value);
        $this->value = $value;
    }

    private function guardAgainstInvalidValue(string $value)
    {
        // @see https://github.com/beberlei/assert for better assertions!!
        if (empty($value)) {
            throw new ValeurIncorrecte('L\'id Acteur ne peut être vide');
        } else if (strtoupper($value) === 'NULL') {
            throw new ValeurIncorrecte('Id Acteur incorrect: ne peut être "NULL"');
        }
    }

    public function getValue() : string
    {
        return $this->value;
    }

    public static function deserialize($value)
    {
        return new static($value['value']);
    }

    public function serialize()
    {
        return $this->getValue();
    }
}