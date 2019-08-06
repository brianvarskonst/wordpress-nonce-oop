<?php

declare(strict_types=1);

namespace NoncesManager\Nonces;

/**
 * Interface NonceAbstractInterface
 *
 * @package NoncesManager\Nonces
 */
interface NonceAbstractInterface
{

    /**
     * Get the nonce
     *
     * @return string $nonce The nonce
     **/
    public function getNonce(): string;

    /**
     * Get the lifetime
     *
     * @param boolean $actualLifetime   Whether to run the 'nonce_life' filter or not. Optional. Default is true.
     *
     * @return int     $lifetime        The current nonce lifetime
     **/
    public function getLifetime(bool $actualLifetime = true): int;

    /**
     * Get the action
     *
     * @return string $action The action
     **/
    public function getAction(): string;

    /**
     * Get the request name
     *
     * @return string $request The request name
     **/
    public function getRequestName(): string;

    /**
     * Returns the nonce value as string.
     *
     * @return string
     */
    public function __toString(): string;
}