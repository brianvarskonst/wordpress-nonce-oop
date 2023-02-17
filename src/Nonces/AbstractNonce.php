<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces;

use Stringable;

abstract class AbstractNonce implements Nonce, Stringable
{
    /**
     * cryptographic token
     */
    protected string $token;

    /**
     * @param string $action The name of the action
     * @param string $requestName The name of the request
     * @param int $lifetime The lifetime of a nonce in seconds
     */
    public function __construct(
        protected string $action,
        protected string $requestName,
        protected int $lifetime = DAY_IN_SECONDS
    ) {

        $this->token = wp_create_nonce($action);
    }

    public function getLifetime(bool $actualLifetime = true): int
    {
        if ($actualLifetime) {
            /**
             * We run $lifetime through the 'nonce_life' to get the actual lifetime, which
             * the system is using right now, since other systems might interfere with
             * this filter.
             */
            return (int) apply_filters('nonce_life', $this->lifetime);
        }

        return $this->lifetime;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getRequestName(): string
    {
        return $this->requestName;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
