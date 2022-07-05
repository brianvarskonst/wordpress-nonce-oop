<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Unit;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Tests\Stubs\FooBarNonce;
use Bvsk\WordPress\NonceManager\Tests\UnitTestCase;

class NonceTest extends UnitTestCase
{
    public function testCreateStubInstance(): void
    {
        $this->assertInstanceOf(
            Nonce::class,
            $this->buildTestee()
        );
    }

    /**
     * @dataProvider defaultDataProvider
     */
    public function testGetterOfStubInstance(
        string $action,
        int $lifetime,
        int $lifetimeManipulated,
        string $requestName,
        string $token
    ): void {

        $nonce = $this->buildTestee();

        $this->assertSame($action, $nonce->getAction());
        $this->assertSame($lifetime, $nonce->getLifetime());
        $this->assertSame($requestName, $nonce->getRequestName());
        $this->assertSame($token, $nonce->getToken());

        $this->assertNotSame($lifetime, $nonce->getLifetime(false));

        $this->assertSame($lifetimeManipulated, $nonce->getLifetime(false));
    }

    public function defaultDataProvider(): iterable
    {
        yield 'test' => FooBarNonce::getDefaults();
    }

    private function buildTestee(): Nonce
    {
        return FooBarNonce::createFromDefaults();
    }
}
