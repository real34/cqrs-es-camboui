<?php

namespace Parlementaires\Domain;

interface Serializable
{
    public static function deserialize($data);
    public function serialize();
}