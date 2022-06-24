<?php

declare(strict_types=1);

namespace NoncesManager\Nonces\Types;

use NoncesManager\Nonces\Nonce;

abstract class AbstractNonceType implements NonceType
{
    protected Nonce $nonce;

    public function __construct(Nonce $nonce)
    {
        $this->nonce = $nonce;
    }

    public function getNonce(): Nonce
    {
        return $this->nonce;
    }
}