<?php


namespace NoncesManager\Nonces;

interface Nonce
{

    /**
     * Get the nonce
     **/
    public function getNonce(): string;

    /**
     * Get the lifetime of the nonce
     *
     * @param boolean $actualLifetime   Whether to run the 'nonce_life' filter or not. Optional. Default is true.
     **/
    public function getLifetime(bool $actualLifetime = true): int;

    /**
     * Get the action
     **/
    public function getAction(): string;

    /**
     * Get the request name
     **/
    public function getRequestName(): string;
}