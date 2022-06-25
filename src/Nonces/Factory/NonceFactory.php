<?php

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;

interface NonceFactory
{
    public function accepts(string $type, array $data): bool;

    public function getSupportedType(): string;

    public function create(string $type, array $data = []): Nonce;
}
