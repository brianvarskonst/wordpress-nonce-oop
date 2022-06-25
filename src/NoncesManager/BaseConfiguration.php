<?php

declare(strict_types=1);

namespace NoncesManager;

/**
 * Class Configuration
 * The Configuration Class
 *
 * @package NoncesManager
 */
class BaseConfiguration implements Configuration
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
     * The lifetime of a nonce in seconds
     **/
    private ?int $lifetime;

    public function __construct(string $action, string $requestName, int $lifetime = null)
    {
        $this->action = $action;
        $this->requestName = $requestName;
        $this->lifetime = $lifetime;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): BaseConfiguration
    {
        $this->action = $action;

        return $this;
    }

    public function getRequestName(): string
    {
        return $this->requestName;
    }

    public function setRequestName(string $requestName): BaseConfiguration
    {
        $this->requestName = $requestName;

        return $this;
    }

    public function getLifetime(): ?int
    {
        return $this->lifetime;
    }

    public function setLifetime(?int $lifetime): BaseConfiguration
    {
        $this->lifetime = $lifetime;

        return $this;
    }
}