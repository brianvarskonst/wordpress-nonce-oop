<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Nonces\UrlNonce;
use Bvsk\WordPress\NonceManager\Nonces\Config\DefaultWordPressNonceConfig as Config;

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
            $data[Config::URL->value],
            $data[Config::ACTION->value] ?? Config::ACTION->getDefault(),
            $data[Config::REQUEST_NAME->value] ?? Config::REQUEST_NAME->getDefault(),
            $this->generateLifetime($data[Config::LIFETIME->value] ?? Config::LIFETIME->getDefault()),
        );
    }
}
