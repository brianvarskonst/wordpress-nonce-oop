<?php

declare(strict_types=1);

namespace NoncesManager;

use NoncesManager\Nonces\NonceFactory;

class NonceManagerBuilder
{
    private NonceFactory $nonceFactory;

    public function __construct(NonceFactory $nonceFactory)
    {
        $this->nonceFactory = $nonceFactory;
    }

    public function withConfiguration(Configuration $configuration): NonceManagerBuilder
    {
        return new NonceManagerBuilder(
            $this->nonceFactory->create($configuration),
            null,
            null,
            null
        );
    }
}