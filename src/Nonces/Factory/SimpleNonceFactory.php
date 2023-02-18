<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Nonces\SimpleNonce;
use Bvsk\WordPress\NonceManager\Nonces\Config\DefaultWordPressNonceConfig as Config;

class SimpleNonceFactory implements NonceFactory
{
    public function accepts(string $type, array $data): bool
    {
        return $type === $this->getSupportedType();
    }

    public function getSupportedType(): string
    {
        return SimpleNonce::class;
    }

    public function create(string $type, array $data = []): Nonce
    {
        return new SimpleNonce(
            $data[Config::ACTION->value] ?? Config::ACTION->getDefault(),
            $data[Config::REQUEST_NAME->value] ?? Config::REQUEST_NAME->getDefault(),
            $this->generateLifetime($data[Config::LIFETIME->value] ?? Config::LIFETIME->getDefault())
        );
    }

    protected function generateLifetime(int $lifetime): int
    {
        /**
         * Double the lifetime because:
         * WordPress uses a system with two ticks (half of the lifetime)
         * and validates nonces from the current tick and the last tick.
         */
        $lifetime *= 2;

        add_filter(
            'nonce_life',
            static fn(int $oldLifetime): int => $lifetime
        );

        return $lifetime;
    }
}
