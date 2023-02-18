<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Integration;

use Bvsk\WordPress\NonceManager\Nonces\Factory\FieldNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Factory\SimpleNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Factory\UrlNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\FieldNonce;
use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Nonces\UrlNonce;
use Bvsk\WordPress\NonceManager\Tests\UnitTestCase;
use Bvsk\WordPress\NonceManager\Verification\NonceVerifier;
use Bvsk\WordPress\NonceManager\Verification\Verifier;
use InvalidArgumentException;

use function Brain\Monkey\Functions\expect;

class NonceVerifierTest extends UnitTestCase
{
    public function testCreateNonceVerifier(): void
    {
        $this->assertInstanceOf(
            Verifier::class,
            $this->buildTestee()
        );
    }

    /**
     * @dataProvider defaultDataProvider
     */
    public function testSuccessfulNonceVerifier(Nonce $nonce): void
    {
        $verifier = $this->buildTestee();

        expect('wp_verify_nonce')->once()->andReturn(true);

        if ($nonce instanceof FieldNonce) {
            expect('check_ajax_referer')->once()->andReturn(true);
        }

        if ($nonce instanceof UrlNonce) {
            expect('check_admin_referer')->once()->andReturn(true);
        }

        $this->assertTrue($verifier->verify($nonce));
    }

    /**
     * @dataProvider defaultDataProvider
     */
    public function testFailingNonceVerifier(Nonce $nonce): void
    {
        $verifier = $this->buildTestee();

        expect('wp_verify_nonce')->once()->andReturn(false);

        if ($nonce instanceof UrlNonce) {
            expect('check_admin_referer')->once()->andReturn(false);

            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage('Invalid UrlNonce was provided.');
        }

        if ($nonce instanceof FieldNonce) {
            expect('check_ajax_referer')->once()->andReturn(false);

            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage('Invalid FieldNonce was provided.');
        }

        $this->assertFalse($verifier->verify($nonce));
    }

    public function defaultDataProvider(): iterable
    {
        expect('add_filter')->times(3);
        expect('wp_create_nonce')->times(3)->andReturn('fooBar');

        yield 'testSimpleNonce' => [
            'nonce' => (new SimpleNonceFactory())->create('simple'),
        ];

        yield 'testFieldNonce' => [
            'nonce' => (new FieldNonceFactory())->create('field', ['referer' => false]),
        ];

        yield 'testUrlNonce' => [
            'nonce' => (new UrlNonceFactory())->create('url', ['url' => 'https://www.example.com']),
        ];
    }

    private function buildTestee(): NonceVerifier
    {
        return new NonceVerifier();
    }
}
