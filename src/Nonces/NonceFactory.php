<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces;

class NonceFactory
{
    public function create(string $action, string $requestName, int $lifetime = null): Nonce
    {
        if ($lifetime) {
            /**
             * Double the lifetime because:
             * WordPress uses a system with two ticks (half of the lifetime) and validates nonces from the current tick and the last tick.
             */
            $lifetime *= 2;

            add_filter(
                'nonce_life',
                static fn(int $oldLifetime): int => $lifetime
            );
        }

        return new SimpleNonce(
            $action,
            $requestName,
            $lifetime
        );
    }
}
