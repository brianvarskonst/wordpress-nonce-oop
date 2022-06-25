<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Nonces\UrlNonce;

class UrlNonceFactory implements NonceFactory
{
    public function accepts(string $type, array $data): bool
    {
        if (!isset($data['url'])) {
            return false;
        }

        return $type === UrlNonce::class;
    }

    public function create(string $type, array $data): Nonce {
        return new UrlNonce(
            (string) $data['url'],
            (string) $data['action'],
            (string) $data['requestName'],
            $this->generateLifetime($data['lifetime']),
        );
    }
}