<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Integration;

use Bvsk\WordPress\NonceManager\NonceManager;
use Bvsk\WordPress\NonceManager\Nonces\Factory\DefaultNonceProperties;
use Bvsk\WordPress\NonceManager\Nonces\FieldNonce;
use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Nonces\SimpleNonce;
use Bvsk\WordPress\NonceManager\Nonces\UrlNonce;
use Bvsk\WordPress\NonceManager\Tests\Stubs\FooBarNonceFactory;
use Bvsk\WordPress\NonceManager\Tests\Stubs\FooBarVerifier;
use Bvsk\WordPress\NonceManager\Tests\UnitTestCase;

use function Brain\Monkey\Functions\expect;

class NonceManagerTest extends UnitTestCase
{
    /**
     * @dataProvider defaultDataProvider
     */
    public function testCreateStubInstance(string $token): void
    {
        $this->assertInstanceOf(
            NonceManager::class,
            $this->buildTesteeWithStubs($token)
        );
    }

    public function testCreateInstanceFromDefaults(): void
    {
        $this->assertInstanceOf(
            NonceManager::class,
            $this->buildTestee()
        );
    }

    /**
     * @dataProvider defaultDataProvider
     */
    public function testCreateSimpleNonceWithNonceManager(string $token): void
    {
        $testee = $this->buildTestee();

        expect('add_filter')->once();
        expect('apply_filters')->once()->andReturn(DefaultNonceProperties::LIFETIME);
        expect('wp_create_nonce')->once()->andReturn($token);

        $nonce = $testee->createNonce(SimpleNonce::class);
        $this->assertInstanceOf(SimpleNonce::class, $nonce);

        $this->assertSame($nonce->getToken(), $token);
        $this->assertSame($nonce->getLifetime(), DefaultNonceProperties::LIFETIME);
        $this->assertSame($nonce->getAction(), DefaultNonceProperties::ACTION);
        $this->assertSame($nonce->getRequestName(), DefaultNonceProperties::REQUEST_NAME);
    }

    /**
     * @dataProvider fieldNonceDataProvider
     */
    public function testCreateFieldNonceWithNonceManager(string $token, string $hiddenInput): void
    {
        $testee = $this->buildTestee();

        expect('add_filter')->once();
        expect('apply_filters')->once()->andReturn(DefaultNonceProperties::LIFETIME);
        expect('wp_create_nonce')->once()->andReturn($token);
        expect('wp_nonce_field')->once()->andReturn($hiddenInput);

        $nonce = $testee->createNonce(FieldNonce::class, ['referer' => false]);
        $this->assertInstanceOf(FieldNonce::class, $nonce);

        $this->assertSame($hiddenInput, $nonce->getField());
        $this->assertSame($token, $nonce->getToken());
        $this->assertSame(DefaultNonceProperties::LIFETIME, $nonce->getLifetime());
        $this->assertSame(DefaultNonceProperties::ACTION, $nonce->getAction());
        $this->assertSame(DefaultNonceProperties::REQUEST_NAME, $nonce->getRequestName());
    }

    /**
     * @dataProvider urlNonceDataProvider
     */
    public function testCreateUrlNonceWithNonceManager(string $token, string $url): void
    {
        $testee = $this->buildTestee();

        expect('add_filter')->once();
        expect('apply_filters')->once()->andReturn(DefaultNonceProperties::LIFETIME);
        expect('wp_create_nonce')->once()->andReturn($token);

        $nonce = $testee->createNonce(UrlNonce::class, ['url' => $url]);
        $this->assertInstanceOf(UrlNonce::class, $nonce);

        $this->assertSame($token, $nonce->getToken());
        $this->assertSame(DefaultNonceProperties::LIFETIME, $nonce->getLifetime());
        $this->assertSame(DefaultNonceProperties::ACTION, $nonce->getAction());
        $this->assertSame(DefaultNonceProperties::REQUEST_NAME, $nonce->getRequestName());
        $this->assertSame($url, $nonce->getUrl());
    }

    /**
     * @dataProvider defaultDataProvider
     */
    public function testVerifyNonceWithNonceManager(string $token): void
    {
        $testee = $this->buildTestee();

        expect('add_filter')->once();
        expect('apply_filters')->once()->andReturn(DefaultNonceProperties::LIFETIME);
        expect('wp_create_nonce')->once()->andReturn($token);

        $nonce = $testee->createNonce(SimpleNonce::class);
        $this->assertInstanceOf(SimpleNonce::class, $nonce);

        expect('wp_verify_nonce')->once()->andReturn(true);

        $this->assertTrue($testee->verify($nonce));
    }

    public function defaultDataProvider(): iterable
    {
        yield 'test' => [
            'token' => 'fooBar',
        ];
    }

    public function fieldNonceDataProvider(): iterable
    {
        yield 'test' => [
            'token' => 'fooBar',
            'hiddenInput' => '<input type="hidden" />',
        ];
    }

    public function urlNonceDataProvider(): iterable
    {
        yield 'test' => [
            'token' => 'fooBar',
            'url' => 'https://example.com',
        ];
    }

    private function buildTestee(): NonceManager
    {
        return NonceManager::createFromDefaults();
    }

    private function buildTesteeWithStubs(string $fooBar): NonceManager
    {
        return new NonceManager(
            new FooBarNonceFactory($fooBar),
            new FooBarVerifier(
                fn(Nonce $nonce): bool => true,
                $fooBar
            )
        );
    }
}
