<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Stubs;

use Bvsk\WordPress\NonceManager\Nonces\Factory\NonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Nonce;

class FooBarNonceFactory implements NonceFactory
{
    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function accepts(string $type, array $data): bool
    {
        return $this->type === $type;
    }

    public function getSupportedType(): string
    {
        return $this->type;
    }

    public function create(string $type, array $data = []): Nonce
    {
        return FooBarNonce::createFromDefaults();
    }
}