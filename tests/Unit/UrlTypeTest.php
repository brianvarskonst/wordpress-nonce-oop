<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests\Unit;

use Brain\Monkey\Functions;
use Bvsk\WordPress\NonceManager\Tests\UnitTestCase;
use NoncesManager\Nonces\Types\UrlType;

/**
 * Class UrlTypeTest
 *
 * @package NoncesManager\Tests\Nonce
 */
class UrlTypeTest extends UnitTestCase
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
     * @var BaseConfiguration
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

        // We mock wp_nonce_url.
        Functions::expect('wp_nonce_url')->andReturnUsing(function ($url, $action, $request_name) {
            return $url . $action . $request_name;
        });

        $this->action = 'action';
        $this->request = 'request';
        $this->lifetime = 213;
        $this->configuration = new Configuration($this->action, $this->request, $this->lifetime);
    }

    /**
     * Test URL generation
     *
     * @covers \NoncesManager\Nonces\Types\UrlType::generate
     */
    public function testCreateNonceURL(): void
    {
        $create = new UrlType($this->configuration);
        $url = 'http://example.com/';
        $url_with_nonce = $create->generate($url);

        self::assertSame($url_with_nonce, $url . $this->action . $this->request);
    }

    /**
     * Test UrlType set & get
     *
     * @covers \NoncesManager\Nonces\Types\UrlType::SetUrl
     * @covers \NoncesManager\Nonces\Types\UrlType::GetUrl
     */
    public function testSetNewNonceUrl(): void {
        $create = new UrlType($this->configuration);
        $url = 'http://example.com/';
        $url_with_nonce = $create->generate($url);

        self::assertSame($url_with_nonce, $create->getUrl());

        // Check if is setted and in util class get back the reflected class
        $setUrl = PHPUnitUtility::callSetterMethod($create, 'setUrl', 'abc');
        $getSettedUrlValue = PHPUnitUtility::callGetterMethod($setUrl, $create, 'getUrl');

        // $create->setUrl('abc');
        self::assertSame($getSettedUrlValue, 'abc');
    }
}
