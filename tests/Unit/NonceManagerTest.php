<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Unit;

use Bvsk\WordPress\NonceManager\NonceManager;
use Bvsk\WordPress\NonceManager\Nonces\Factory\DefaultNonceProperties;
use Bvsk\WordPress\NonceManager\Nonces\Factory\NonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\FieldNonce;
use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Nonces\SimpleNonce;
use Bvsk\WordPress\NonceManager\Nonces\UrlNonce;
use Bvsk\WordPress\NonceManager\Tests\UnitTestCase;
use Bvsk\WordPress\NonceManager\Verification\Verifier;
use function Brain\Monkey\Functions\expect;

class NonceManagerTest extends UnitTestCase
{
    public function testCreateNewInstanceOfNonceManagerWithDependencyInjection(): void
    {
        $testee = $this->buildTesteeWithAnonymousDependencies();

        $this->assertInstanceOf(NonceManager::class, $testee);
    }

    public function testCreateNewInstanceOfNonceManagerFromDefaults(): void
    {
        $testee = $this->buildTestee();

        $this->assertInstanceOf(NonceManager::class, $testee);
    }

    public function testCreateSimpleNonceWithNonceManager(): void
    {
        $testee = $this->buildTestee();

        $token = 'fooBar';

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

    public function testCreateFieldNonceWithNonceManager(): void
    {
        $testee = $this->buildTestee();

        $token = 'fooBar';
        $hiddenInput = '<input type="hidden" />';

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

    public function testCreateUrlNonceWithNonceManager(): void
    {
        $testee = $this->buildTestee();

        $token = 'fooBar';
        $url = 'https://example.com';

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

    public function testVerifyNonceWithNonceManager(): void
    {
        $testee = $this->buildTestee();

        $token = 'fooBar';

        expect('add_filter')->once();
        expect('apply_filters')->once()->andReturn(DefaultNonceProperties::LIFETIME);
        expect('wp_create_nonce')->once()->andReturn($token);

        $nonce = $testee->createNonce(SimpleNonce::class);
        $this->assertInstanceOf(SimpleNonce::class, $nonce);

        expect('wp_verify_nonce')->once()->andReturn(true);

        $this->assertTrue($testee->verify($nonce));
    }

    private function buildTestee(): NonceManager
    {
        return NonceManager::createFromDefaults();
    }

    private function buildTesteeWithAnonymousDependencies(): NonceManager
    {
        return new NonceManager(
            new class implements NonceFactory {

                public function accepts(string $type, array $data): bool
                {
                    return true;
                }

                public function getSupportedType(): string
                {
                    return 'FooBar';
                }

                public function create(string $type, array $data = []): Nonce
                {
                    return new class implements Nonce {

                        public function getAction(): string
                        {
                            return 'foo';
                        }

                        public function getLifetime(bool $actualLifetime = true): int
                        {
                            return 1;
                        }

                        public function getRequestName(): string
                        {
                            return 'bar';
                        }

                        public function getToken(): string
                        {
                            return 'fooBar';
                        }
                    };
                }
            },
            new class implements Verifier {

                public function verify(Nonce $nonce): bool
                {
                    return true;
                }

                public function getAge(Nonce $nonce): string
                {
                    return '1';
                }

                public function renderMessageHasExpired(Nonce $nonce): void
                {}
            }
        );
    }
}