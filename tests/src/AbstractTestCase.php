<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Tests;

use PHPUnit\Framework\TestCase;

use Brain\Monkey;

use function defined;
use function define;

/**
 * Class AbstractTestCase
 *
 * @package NoncesManager\Tests
 */
abstract class AbstractTestCase extends TestCase
{

    /**
     * Define the used standard WordPress Time Interval
     */
    private function defineDefaultTimeInterval(): void {
        if (!defined('DAY_IN_SECONDS')) {
            define('DAY_IN_SECONDS', 86400);
        }
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     **/
    protected function setUp(): void
    {
        $this->defineDefaultTimeInterval();

        parent::setUp();
        Monkey::setUpWP();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     **/
    public function tearDown(): void {
        Monkey::tearDownWP();
        parent::tearDown();
    }
}