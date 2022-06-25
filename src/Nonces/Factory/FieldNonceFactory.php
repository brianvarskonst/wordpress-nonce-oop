<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\FieldNonce;
use Bvsk\WordPress\NonceManager\Nonces\Nonce;

class FieldNonceFactory extends SimpleNonceFactory
{
    public function accepts(string $type, array $data): bool
    {
        if (!isset($data['referer'])) {
            return false;
        }

        return $type === FieldNonce::class;
    }

    public function create(string $type, array $data): Nonce
    {
        return new FieldNonce(
            (string) $data['action'],
            (string) $data['requestName'],
            $this->generateLifetime($data['lifetime']),
            (bool) $data['referer']
        );
    }
}