<?php

declare(strict_types=1);

namespace NoncesManager\Tests\Nonce;

use Brain\Monkey\Functions;
use NoncesManager\Nonces\Types\UrlType;
use NoncesManager\Tests\AbstractTestCase;

use NoncesManager\BaseConfiguration;
use NoncesManager\Nonces\Types\FieldType;
use NoncesManager\Tests\Utility\PHPUnitUtility;

/**
 * Class FieldTypeTest
 *
 * @package NoncesManager\Tests\Nonce
 */
class FieldTypeTest extends AbstractTestCase
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
    public function setUp(): void
    {
        parent::setUp();

        // we mock wp_create_nonce with sha1().
        Functions::when('wp_create_nonce')->alias('sha1');

        // we mock wp_nonce_field.
        Functions::expect('wp_nonce_field')->andReturnUsing(function ($action, $request_name, $referer, $echo) {
            $string = $action . $request_name;
            if ($referer) {
                $string .= 'referer';
            }

            // Should never be true, since we call wp_nonce_field with $echo false always and do echo by ourselfs.
            if ($echo) {
                $string .= 'echo';
            }

            return $string;
        });

        // we mock wp_kses.
        Functions::expect('wp_kses')->andReturnUsing(function ($string, $array) {
            return $string;
        });

        $this->action = 'action';
        $this->request = 'request';
        $this->lifetime = 213;
        $this->configuration = new Configuration($this->action, $this->request, $this->lifetime);
    }

    /**
     * Test Field generation & output
     *
     * @covers \NoncesManager\Nonces\Types\FieldType::generate
     */
    public function testGenerateNonceField(): void
    {
        $create = new FieldType($this->configuration);
        $create->generate();
        $field = $create->getField();

        self::assertSame($field, $this->action . $this->request);

        $field = $create->generate(true);
        self::assertSame($field, $this->action . $this->request . 'referer');

        // Test echo.
        ob_start();
        $create->generate(false, true);
        $field = $create->getField();
        $echo_output = ob_get_contents();
        ob_end_clean();

        self::assertSame($echo_output, $this->action . $this->request . 'echo');
        self::assertSame($echo_output, $field);
    }

    /**
     * Test FieldType set & get
     *
     * @covers \NoncesManager\Nonces\Types\UrlType::SetUrl
     * @covers \NoncesManager\Nonces\Types\UrlType::GetUrl
     */
    public function testSetNewNonceField(): void {
        $create = new FieldType($this->configuration);
        $create->generate();
        $field = $create->getField();

        // Check if is setted and in util class get back the reflected class
        $setUrl = PHPUnitUtility::callSetterMethod($create, 'setField', $this->action . $this->request);
        $getSettedFieldValue = PHPUnitUtility::callGetterMethod($setUrl, $create, 'getField');

        // $create->setUrl('abc');
        self::assertSame($getSettedFieldValue, $field);
    }
}
