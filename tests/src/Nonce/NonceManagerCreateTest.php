<?php

declare(strict_types=1);

namespace NoncesManager\Tests\Nonce;

use Brain\Monkey\WP\Filters;
use NoncesManager\Nonces\Nonce;
use NoncesManager\Tests\AbstractTestCase;

use NoncesManager\Configuration;
use NoncesManager\NonceManager;
use NoncesManager\Nonces\Types\FieldTypeNonce;
use NoncesManager\Nonces\Types\UrlType;
use NoncesManager\Nonces\Verification\Verifier;

class NonceManagerCreateTest extends AbstractTestCase
{
    /**
     * The Request Name.
     *
     * @var string
     **/
    public $request;

    /**
     * The Action.
     *
     * @var string
     **/
    public $action;

    /**
     * The Lifetime.
     *
     * @var int
     **/
    public $lifetime;

    /**
     * The Configuration.
     *
     * @var Configuration
     **/
    public $configuration;

    /**
     * The NonceManager
     *
     * @var NonceManager
     */
    private $nonceManager;

    /**
     * @var FieldTypeNonce
     */
    private $fieldType;

    /**
     * @var UrlType
     */
    private $urlType;

    /**
     * @var Verifier
     */
    private $verifier;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->action = 'action';
        $this->request = 'request';
        $this->lifetime = 213;

        // The filter should be added once.
        Filters::expectAdded('nonce_life')->once();

        $this->configuration = new Configuration($this->action, $this->request, $this->lifetime);

        $this->fieldType = new FieldType($this->configuration);
        $this->urlType = new UrlType($this->configuration);
        $this->verifier = new NonceVerifier($this->configuration);
    }

    /**
     * Test create a new Nonce Manager through the static create method and inject the required Dependencies
     *
     * @covers \NoncesManager\NonceManager::create
     */
    public function testCreatedNonceManager(): void {
        $this->nonceManager = NonceManager::create($this->configuration, $this->fieldType, $this->urlType, $this->verifier);

        self::assertInstanceOf(NonceManager::class, $this->nonceManager);
    }

    /**
     * Test the created Nonce Manager and test the inject Dependencies
     *
     * @covers \NoncesManager\NonceManager::Configuration
     * @covers \NoncesManager\NonceManager::Nonce
     * @covers \NoncesManager\NonceManager::Field
     * @covers \NoncesManager\NonceManager::Url
     * @covers \NoncesManager\NonceManager::Verifier
     */
    public function testServicesAreInjected(): void {
        $this->nonceManager = NonceManager::create($this->configuration, $this->fieldType, $this->urlType, $this->verifier);

        self::assertInstanceOf(Configuration::class, $this->nonceManager->Configuration());
        self::assertInstanceOf(Nonce::class, $this->nonceManager->Nonce());
        self::assertInstanceOf(FieldType::class, $this->nonceManager->Field());
        self::assertInstanceOf(UrlNonce::class, $this->nonceManager->Url());
        self::assertInstanceOf(Verifier::class, $this->nonceManager->Verifier());
    }
}