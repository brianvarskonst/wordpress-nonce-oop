<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Unit;

use Bvsk\WordPress\NonceManager\Tests\UnitTestCase;
use NoncesManager\Nonces\Nonce;
use Brain\Monkey\WP\Filters;
use NoncesManager\BaseConfiguration;
use NoncesManager\NonceManager;
use NoncesManager\Nonces\Types\FieldType;
use NoncesManager\Nonces\Types\UrlType;
use NoncesManager\Nonces\Verification\Verifier;

class NonceManagerBuildTest extends UnitTestCase
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
     * @var FieldType
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
    public function setUp(): void
    {
        parent::setUp();

        $this->action = 'action';
        $this->request = 'request';
        $this->lifetime = 213;

        // The filter should be added once.
        Filters::expectAdded('nonce_life')->once();

        $this->configuration = new BaseConfiguration($this->action, $this->request, $this->lifetime);

        $this->nonceManager = NonceManager::build($this->configuration);
    }

    /**
     * Test create a new Nonce Manager through the static create method and inject the required Dependencies
     *
     * @covers \NoncesManager\NonceManager::build
     */
    public function testBuildedNonceManager(): void
    {
        self::assertInstanceOf(NonceManager::class, $this->nonceManager);
    }

    /**
     * Test the builded Configuration
     *
     * @covers \NoncesManager\NonceManager::Configuration
     */
    public function testGetConfiguration(): void
    {
        self::assertInstanceOf(BaseConfiguration::class, $this->nonceManager->Configuration());
    }

    /**
     * Test the builded Nonce
     *
     * @covers \NoncesManager\NonceManager::Nonce
     */
    public function testGetNonce(): void
    {
        self::assertInstanceOf(Nonce::class, $this->nonceManager->Nonce());
    }

    /**
     * Test the builded FieldType
     *
     * @covers \NoncesManager\NonceManager::Field
     */
    public function testGetField(): void
    {
        self::assertInstanceOf(FieldType::class, $this->nonceManager->Field());
    }

    /**
     * Test the builded UrlType
     *
     * @covers \NoncesManager\NonceManager::Url
     */
    public function testGetUrl(): void
    {
        self::assertInstanceOf(UrlType::class, $this->nonceManager->Url());
    }

    /**
     * Test the builded Verifier
     *
     * @covers \NoncesManager\NonceManager::Verifier
     */
    public function testGetVerifier(): void
    {
        self::assertInstanceOf(NonceVerifier::class, $this->nonceManager->Verifier());
    }
}