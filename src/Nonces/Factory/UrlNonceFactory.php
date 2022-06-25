<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Nonces\UrlNonce;

class UrlNonceFactory extends SimpleNonceFactory
{
    public function accepts(string $type, array $data): bool
    {
        if (!isset($data['url'])) {
            return false;
        }

        return $type === $this->getSupportedType();
    }

    public function getSupportedType(): string
    {
        return UrlNonce::class;
    }

    public function create(string $type, array $data = []): Nonce
    {

        return new UrlNonce(
            $data['url'],
            $data['action'] ?? DefaultNonceProperties::ACTION,
            $data['requestName'] ?? DefaultNonceProperties::REQUEST_NAME,
            $this->generateLifetime($data['lifetime'] ?? DefaultNonceProperties::LIFETIME),
        );
    }
}
