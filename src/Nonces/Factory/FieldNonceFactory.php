<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\FieldNonce;
use Bvsk\WordPress\NonceManager\Nonces\Nonce;

class FieldNonceFactory extends SimpleNonceFactory
{
    public function getSupportedType(): string
    {
       return FieldNonce::class;
    }

    public function create(string $type, array $data = []): Nonce
    {
        return new FieldNonce(
            $data['action'] ?? DefaultNonceProperties::ACTION,
            $data['requestName'] ?? DefaultNonceProperties::REQUEST_NAME,
            $this->generateLifetime($data['lifetime'] ?? DefaultNonceProperties::LIFETIME),
            (bool) $data['referer']
        );
    }
}
