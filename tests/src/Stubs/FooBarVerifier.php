<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Stubs;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Verification\Verifier;

class FooBarVerifier implements Verifier
{
    private $callback;

    private string $age;

    public function __construct(
        callable $callback,
        string $age
    ) {

        $this->callback = $callback;
        $this->age = $age;
    }

    public function verify(Nonce $nonce): bool
    {
        return ($this->callback)($nonce);
    }

    public function getAge(Nonce $nonce): string
    {
        return $this->age;
    }

    public function renderMessageHasExpired(Nonce $nonce): void
    {
        echo $this->age;
    }
}