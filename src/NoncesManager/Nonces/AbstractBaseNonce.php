<?php

declare(strict_types=1);

namespace NoncesManager\Nonces;

use Stringable;

abstract class AbstractBaseNonce implements Nonce, Stringable
{
    /**
     * The name of the action
     **/
    private string $action;

    /**
     * The name of the request
     **/
    private string $requestName;

    /**
     * The nonce
     **/
    private string $nonce;

    /**
     * The lifetime of a nonce in seconds
     **/
    private int $lifetime;

    public function __construct(
        string $action,
        string $requestName,
        string $nonce,
        int $lifetime = DAY_IN_SECONDS
    ) {

        $this->action = $action;
        $this->requestName = $requestName;
        $this->nonce = $nonce;
        $this->lifetime = $lifetime;
    }

    protected function setNonce(string $nonce): self
    {
        $this->nonce = $nonce;

        return $this;
    }

    public function getNonce(): string
    {
        return $this->nonce;
    }

    protected function setLifetime(int $lifetime): self
    {
        $this->lifetime = $lifetime;

        return $this;
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

    protected function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    protected function setRequestName(string $requestName): self
    {
        $this->requestName = $requestName;

        return $this;
    }

    public function getRequestName(): string
    {
        return $this->requestName;
    }

    public function __toString(): string
    {
        return $this->getNonce();
    }
}