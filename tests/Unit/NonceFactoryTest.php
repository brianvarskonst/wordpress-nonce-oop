<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Unit;

use Bvsk\WordPress\NonceManager\Nonces\Factory\NonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Tests\Stubs\FooBarNonce;
use Bvsk\WordPress\NonceManager\Tests\Stubs\FooBarNonceFactory;
use Bvsk\WordPress\NonceManager\Tests\UnitTestCase;

class NonceFactoryTest extends UnitTestCase
{
    /**
     * @dataProvider defaultDataProvider
     */
    public function testCreateStubInstance(string $type): void
    {
        $this->assertInstanceOf(
            NonceFactory::class,
            $this->buildTestee($type)
        );
    }

    /**
     * @dataProvider defaultDataProvider
     */
    public function testStubInstance(string $type): void
    {
        $nonceFactory = $this->buildTestee($type);

        $this->assertTrue($nonceFactory->accepts($type, []));
        $this->assertSame($type, $nonceFactory->getSupportedType());

        $nonce = $nonceFactory->create($type, []);

        $this->assertInstanceOf(Nonce::class, $nonce);
        $this->assertInstanceOf(FooBarNonce::class, $nonce);
    }

    public function defaultDataProvider(): iterable
    {
        yield 'test' => [
            'type' => 'fooBar'
        ];

        yield 'test2' => [
            'type' => 'fizzBuzz'
        ];
    }

    private function buildTestee(string $type): FooBarNonceFactory
    {
        return new FooBarNonceFactory($type);
    }
}