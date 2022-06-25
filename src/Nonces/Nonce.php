<?php


namespace Bvsk\WordPress\NonceManager\Nonces;

interface Nonce
{
    public function getAction(): string;

    /**
     * @param boolean $actualLifetime   Whether to run the 'nonce_life' filter or not. Optional. Default is true.
     **/
    public function getLifetime(bool $actualLifetime = true): int;

    public function getRequestName(): string;

    public function getToken(): string;
}