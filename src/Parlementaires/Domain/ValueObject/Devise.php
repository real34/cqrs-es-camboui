<?php

namespace Parlementaires\Domain\ValueObject;

class Devise
{
    private $code;

    public function __construct(string $code)
    {
        $this->guardAgainsUnsupportedCode($code);
        $this->code = $code;
    }

    private function guardAgainsUnsupportedCode($code)
    {
        if ($code !== 'EUR') {
            throw new ValeurIncorrecte('Nous ne supportons que les suventions en Euro !');
        }
    }

    public function getCode() : string
    {
        return $this->code;
    }
}