<?php

declare(strict_types=1);

namespace NoncesManager\Tests\Nonce;

use NoncesManager\Tests\AbstractTestCase;

use Brain\Monkey\Functions;

use NoncesManager\BaseConfiguration;
use NoncesManager\Nonces\Nonce;
use NoncesManager\Nonces\Verification\Verifier;

/**
 * Class VerifierTest
 *
 * @package NoncesManager\Tests\Nonce
 */
class VerifierTest extends AbstractTestCase
{

    /**
     * The request name.
     *
     * @var string
     **/
    public $request;

    /**
     * The action.
     *
     * @var string
     **/
    public $action;

    /**
     * The lifetime.
     *
     * @var int
     **/
    public $lifetime;

    /**
     * The configuration.
     *
     * @var Configuration
     **/
    public $configuration;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     **/
    public function setUp(): void
    {
        parent::setUp();

        // we mock wp_create_nonce with sha1().
        Functions::when('wp_create_nonce')->alias('sha1');

        // we mock wp_verify_nonce.
        Functions::expect('wp_verify_nonce')->andReturnUsing(function ($nonce, $action) {
            return sha1($action) === $nonce;
        });

        // we mock wp_unslash.
        Functions::expect('wp_unslash')->andReturnUsing(function ($string) {
            return $string;
        });

        // we mock sanitize_text_field.
        Functions::expect('sanitize_text_field')->andReturnUsing(function ($string) {
            return $string;
        });

        $this->action = 'action';
        $this->request = 'request';
        $this->lifetime = 213;
        $this->configuration = new Configuration($this->action, $this->request, $this->lifetime);
    }

    /**
     * Check validation
     *
     * @covers \NoncesManager\Nonces\Verification\Verifier::verify
     */
    public function testValidity(): void
    {
        $create = new Nonce($this->configuration);
        $create->create();
        $nonce = $create->getNonce();

        $verify = new Verifier($this->configuration);
        $valid = $verify->verify($nonce);

        // Check if nonce is valid.
        self::assertTrue($valid);

        // Check if nonce is not valid.
        $not_valid = $verify->verify('not-valid' . $nonce);
        self::assertFalse($not_valid);

        // Check auto-nonce assignment.
        $_REQUEST[$this->request] = $nonce;
        $verify = new NonceVerifier($this->configuration);
        $valid = $verify->verify();
        self::assertTrue($valid);
    }

    /**
     * Test Get Age
     *
     * @covers \NoncesManager\Nonces\Verification\Verifier::getAge
     */
    public function testAge(): void
    {
        self::markTestSkipped('wp_verify_nonce() needs a better mockup to test this functionality.');

        $create = new Nonce($this->configuration);
        $create->create();

        $verify = new NonceVerifier($this->configuration);
        $age = $verify->getAge();

        self::assertSame(1, $age);
    }

    /**
     * Test check Admin Referer valid
     * TODO: Make better Test
     *
     * @covers \NoncesManager\Nonces\Verification\Verifier::isAdminReferer
     */
    public function testCheckAdminRefererValid(): void {
        $create = new Nonce($this->configuration);
        $create->create();

        // mock check_admin_referer success
        Functions::expect('check_admin_referer')
            ->once()
            ->with($this->action, $this->request)
            ->andReturn(true);

        $verify = new Verifier($this->configuration);
        $valid = $verify->isAdminReferer();

        // Check if nonce is valid.
        self::assertTrue($valid);
    }

    /**
     * Test check Admin Referer invalid
     * TODO: Make better Test
     *
     * @covers \NoncesManager\Nonces\Verification\Verifier::isAdminReferer
     */
    public function testCheckAdminRefererInvalid(): void {
        $create = new Nonce($this->configuration);
        $create->create();

        // mock check_admin_referer success
        Functions::expect('check_admin_referer')
            ->once()
            ->with($this->action, $this->request)
            ->andReturn(false);

        $verify = new Verifier($this->configuration);
        $valid = $verify->isAdminReferer();

        // Check if nonce is invalid.
        self::assertFalse($valid);
    }

    /**
     * Test check Ajax Referer valid
     * TODO: Make better Test
     *
     * @covers \NoncesManager\Nonces\Verification\Verifier::isAjaxReferer
     */
    public function testCheckAjaxRefererValid(): void {
        $create = new Nonce($this->configuration);
        $create->create();

        // mock check_admin_referer success
        Functions::expect('check_ajax_referer')
            ->once()
            ->with($this->action, $this->request, false)
            ->andReturn(true);

        $verify = new Verifier($this->configuration);
        $valid = $verify->isAjaxReferer();

        // Check if nonce is valid.
        self::assertTrue($valid);
    }

    /**
     * Test check Ajax Referer valid
     * TODO: Make better Test
     *
     * @covers \NoncesManager\Nonces\Verification\Verifier::isAjaxReferer
     */
    public function testCheckAjaxRefererInvalid(): void {
        $create = new Nonce($this->configuration);
        $create->create();

        // mock check_admin_referer success
        Functions::expect('check_ajax_referer')
            ->once()
            ->with($this->action, $this->request, false)
            ->andReturn(false);

        $verify = new Verifier($this->configuration);
        $valid = $verify->isAjaxReferer();

        // Check if nonce is valid.
        self::assertFalse($valid);
    }
}
