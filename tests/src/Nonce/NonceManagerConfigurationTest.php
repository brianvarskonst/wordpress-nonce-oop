<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Nonce;

use Brain\Monkey\WP\Filters;

use NoncesManager\Tests\AbstractTestCase;

use NoncesManager\Configuration;

/**
 * Class NonceManagerConfigurationTest
 *
 * @package NoncesManager\Tests\Nonce
 */
class NonceManagerConfigurationTest extends AbstractTestCase
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
     * @var BaseConfiguration
     **/
    public $configuration;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     **/
    public function setUp() : void
    {
        parent::setUp();

        $this->action = 'action';
        $this->request = 'request';
    }

    /**
     * Check if NonceConfig stores the data correctly.
     *
     * @covers \NoncesManager\Configuration::getAction
     * @covers \NoncesManager\Configuration::getRequestName
     * @covers \NoncesManager\Configuration::getLifetime
     *
     * @covers \NoncesManager\Configuration::nonceLifetime
     */
    public function testCreateConfiguration(): void
    {
        $this->lifetime = 213;

        // The filter should be added once.
        Filters::expectAdded('nonce_life')->once();

        $this->configuration = new BaseConfiguration($this->action, $this->request, $this->lifetime);

        self::assertSame($this->configuration->getAction(), $this->action);
        self::assertSame($this->configuration->getRequestName(), $this->request);

        // Not the same as setted, because 1 tick is not one second
        self::assertNotSame($this->configuration->getLifetime(), $this->lifetime);

        // Handle Tick Time Interval
        $lifetime = $this->lifetime * 2;

        // Check if nonceLifetime returns the right value.
        self::assertSame($this->configuration->nonceLifetime(DAY_IN_SECONDS), $lifetime);
    }

    /**
     * Check if filter is not added, when lifetime is not set.
     **/
    public function testNoFilterAdded(): void
    {
        $this->lifetime = null;

        // The filter should be added once.
        Filters::expectAdded('nonce_life')->never();

        $this->configuration = new BaseConfiguration($this->action, $this->request, $this->lifetime);

        self::assertNotSame($this->configuration->nonceLifetime(DAY_IN_SECONDS), $this->lifetime);
    }
}
