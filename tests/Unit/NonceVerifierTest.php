<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Unit;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Tests\Stubs\FooBarNonce;
use Bvsk\WordPress\NonceManager\Tests\Stubs\FooBarVerifier;
use Bvsk\WordPress\NonceManager\Tests\UnitTestCase;
use Bvsk\WordPress\NonceManager\Verification\Verifier;

class NonceVerifierTest extends UnitTestCase
{
    /**
     * @dataProvider defaultDataProvider
     */
    public function testCreateStubInstance(
        callable $callback,
        string $age
    ): void {

        $this->assertInstanceOf(
            Verifier::class,
            $this->buildtestee($callback, $age)
        );
    }

    /**
     * @dataProvider defaultDataProvider
     */
    public function testStubInstance(
        callable $callback,
        string $age,
        Nonce $nonce
    ): void {

        $verifier = $this->buildtestee($callback, $age);

        $this->assertTrue($verifier->verify($nonce));
        $this->assertSame($age, $verifier->getAge($nonce));

        $this->expectOutputString($age);
        $verifier->renderMessageHasExpired($nonce);
    }

    public function defaultDataProvider(): iterable
    {
        yield 'test' => [
            'callback' => static fn(Nonce $nonce): bool => true,
            'age' => 'fooBar',
            'nonce' => FooBarNonce::createFromDefaults(),
        ];
    }

    private function buildtestee(
        callable $callback,
        string $age
    ): FooBarVerifier {

        return new FooBarVerifier($callback, $age);
    }
}
