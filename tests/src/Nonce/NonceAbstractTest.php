<?php

declare(strict_types=1);

namespace NoncesManager\Tests\Nonce;

use Brain\Monkey\Functions;
use NoncesManager\Nonces\NonceAbstract;
use NoncesManager\Tests\AbstractTestCase;

use NoncesManager\Configuration;
use NoncesManager\Nonces\Nonce;

class NonceAbstractTest extends AbstractTestCase
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
     * @var Configuration
     **/
    public $configuration;

    public $nonce;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     **/
    public function setUp() : void
    {
        parent::setUp();

        // We mock wp_create_nonce with sha1().
        Functions::when('wp_create_nonce')->alias('sha1');

        $this->action = 'action';
        $this->request = 'request';
        $this->lifetime = 213;
        $this->configuration = new Configuration($this->action, $this->request, $this->lifetime);

        // Get mock, without the constructor being called
        $this->nonce = new Nonce($this->configuration);
    }

    /**
     * Test Nonce is an instance of NonceAbstract Class
     * TODO: Maybe make a better Test
     */
    public function testNonceAbstractClassNonceInstance(): void {
        $this->assertInstanceOf(NonceAbstract::class, $this->nonce);
    }

    /**
     * Test Create setted & get an Nonce
     *
     * @covers \NoncesManager\Nonces\NonceAbstract::setNonce
     * @covers \NoncesManager\Nonces\NonceAbstract::getNonce
     */
    public function testNonceAbstractClassNonce(): void {
        $create = new Nonce($this->configuration);

        $this->assertSame($create->getNonce(), $this->nonce->getNonce());
    }

    /**
     * Test setted and get Nonce Lifetime
     *
     * @covers \NoncesManager\Nonces\NonceAbstract::setLifetime
     * @covers \NoncesManager\Nonces\NonceAbstract::getLifetime
     */
    public function testNonceAbstractClassNonceLifetime(): void {
        //Handle ticks
        $lifetime = $this->lifetime * 2;

        $this->assertSame($lifetime, $this->nonce->getLifetime());
    }

    /**
     * Test setted and get Nonce Action
     *
     * @covers \NoncesManager\Nonces\NonceAbstract::setAction
     * @covers \NoncesManager\Nonces\NonceAbstract::getAction
     */
    public function testNonceAbstractClassNonceAction(): void {
        $this->assertSame($this->action, $this->nonce->getAction());
    }

    /**
     * Test setted and get Nonce RequestName
     *
     * @covers \NoncesManager\Nonces\NonceAbstract::setRequestName
     * @covers \NoncesManager\Nonces\NonceAbstract::getRequestName
     */
    public function testNonceAbstractClassNonceRequestName(): void {
        $this->assertSame($this->request, $this->nonce->getRequestName());
    }
}