<?php

declare(strict_types=1);

namespace NoncesManager\Nonces;

use Stringable;

abstract class AbstractNonce implements Nonce, Stringable
{
    /**
     * The name of the action
     **/
    protected string $action;

    /**
     * The name of the request
     **/
    protected string $requestName;

    /**
     * The lifetime of a nonce in seconds
     **/
    protected int $lifetime;

    /**
     * cryptographic token
     */
    protected string $token;

    public function __construct(
        string $action,
        string $requestName,
        int $lifetime = DAY_IN_SECONDS
    ) {

        $this->action = $action;
        $this->requestName = $requestName;
        $this->lifetime = $lifetime;
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
        return $this->getToken();
    }
}