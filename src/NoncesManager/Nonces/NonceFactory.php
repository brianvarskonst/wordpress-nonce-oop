<?php

declare(strict_types=1);

namespace NoncesManager\Nonces;

use NoncesManager\Configuration;

class NonceFactory
{
    public function create(Configuration $configuration): Nonce
    {
        return new BaseNonce(
            $configuration->getAction(),
            $configuration->getRequestName(),
            $configuration->getNonce(),
            $configuration->getLifetime()
        );
    }
}