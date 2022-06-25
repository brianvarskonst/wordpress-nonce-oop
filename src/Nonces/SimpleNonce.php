<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces;

class SimpleNonce extends AbstractNonce
{

    public function check(): bool
    {
        return !empty($this->getToken());
    }

    public function refresh(): Nonce
    {
        $this->token = wp_create_nonce($this->action);

        return $this;
    }
}