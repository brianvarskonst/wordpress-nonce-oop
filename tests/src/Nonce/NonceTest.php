<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Nonce;

use NoncesManager\Tests\AbstractTestCase;

use Brain\Monkey\Functions;

use NoncesManager\BaseConfiguration;
use NoncesManager\Nonces\Nonce;

/**
 * Class NonceTest
 *
 * @package NoncesManager\Tests\Nonce
 */
class NonceTest extends AbstractTestCase
{

    /**
     * The action.
     *
     * @var string
     **/
    public $action;

    /**
     * The request name.
     *
     * @var string
     **/
    public $request;

    /**
     * The lifetime.
     *
     * @var int
     **/
    public $lifetime;

    /**
     * The configuration.
     *
     * @var \NoncesManager\Configuration
     **/
    public $configuration;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     **/
    public function setUp(): void
    {
        parent::setUp();

        // We mock wp_create_nonce with sha1().
        Functions::when('wp_create_nonce')->alias('sha1');

        $this->action = 'action';
        $this->request = 'request';
        $this->lifetime = 213;
        $this->configuration = new BaseConfiguration($this->action, $this->request, $this->lifetime);
    }

    /**
     * Test Create new Nonce & get the new Nonce
     *
     * @covers \NoncesManager\Nonces\Nonce::create
     * @covers \NoncesManager\Nonces\Nonce::getAction
     */
    public function testCreateNonce(): void
    {
        $create = new Nonce($this->configuration);
        $create->create();
        $nonce = $create->getNonce();

        // Check if nonce is stored correctly.
        self::assertSame($nonce, $create->getNonce());
    }

    /**
     * Test the new created Nonce Magic Method __toString
     *
     * @covers \NoncesManager\Nonces\Nonce::__toString
     */
    public function testMagicMethodToString() {
        $create = new Nonce($this->configuration);
        $create->create();
        $nonceToString = (string) $create;

        $nonce = $create->getNonce();

        self::assertSame($nonceToString, $nonce);
    }
}
