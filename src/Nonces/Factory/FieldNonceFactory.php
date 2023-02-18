<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\FieldNonce;
use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Nonces\Config\DefaultWordPressNonceConfig as Config;

class FieldNonceFactory extends SimpleNonceFactory
{
    public function getSupportedType(): string
    {
        return FieldNonce::class;
    }

    public function create(string $type, array $data = []): Nonce
    {
        return new FieldNonce(
            $data[Config::ACTION->value] ?? Config::ACTION->getDefault(),
            $data[Config::REQUEST_NAME->value] ?? Config::REQUEST_NAME->getDefault(),
            $this->generateLifetime($data[Config::LIFETIME->value] ?? Config::LIFETIME->getDefault()),
            (bool) $data[Config::REFERER->value]
        );
    }
}
