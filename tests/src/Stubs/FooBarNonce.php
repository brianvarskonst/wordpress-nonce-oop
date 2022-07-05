<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Stubs;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;

class FooBarNonce implements Nonce
{
    private string $action;

    private int $lifetime;

    private int $lifetimeManipulated;

    private string $requestName;

    private string $token;

    public function __construct(
        string $action,
        int $lifetime,
        int $lifetimeManipulated,
        string $requestName,
        string $token
    ) {

        $this->action = $action;
        $this->lifetime = $lifetime;
        $this->lifetimeManipulated = $lifetimeManipulated;
        $this->requestName = $requestName;
        $this->token = $token;
    }

    public static function createFromDefaults(): Nonce
    {
        $data = self::getDefaults();

        return new self(
            $data['action'],
            $data['lifetime'],
            $data['lifetimeManipulated'],
            $data['requestName'],
            $data['token']
        );
    }

    public static function getDefaults(): array
    {
        $fooBar = 'fooBar';
        $lifetime = 1;

        return [
            'action' => $fooBar,
            'lifetime' => $lifetime,
            'lifetimeManipulated' => $lifetime * 2,
            'requestName' => $fooBar,
            'token' => $fooBar
        ];
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getLifetime(bool $actualLifetime = true): int
    {
        if (!$actualLifetime) {
            return $this->lifetimeManipulated;
        }

        return $this->lifetime;
    }

    public function getRequestName(): string
    {
        return $this->requestName;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}