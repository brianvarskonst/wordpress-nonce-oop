<?php

declare(strict_types=1);

namespace NoncesManager\Nonces\Types;

interface NonceType
{
    public function generate(): string;
}