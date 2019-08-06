<?php

declare(strict_types=1);

namespace NoncesManager;

use NoncesManager\Nonces\NonceAbstract;

/**
 * Class Configuration
 * The Configuration Class
 *
 * @package NoncesManager
 */
class Configuration extends NonceAbstract implements ConfigurationInterface
{

    /**
     * NonceSetting constructor.
     *
     * @param $action string Action
     * @param $request_name string The new request name
     * @param $lifetime int Lifetime of the nonce
     *
     * @return void
     */
    public function __construct(string $action, string $request_name, int $lifetime = null)
    {

        $this->setAction($action);
        $this->setRequestName($request_name);

        if ($lifetime !== null) {
            /**
             * Double the lifetime because:
             * WordPress uses a system with two ticks (half of the lifetime) and validates nonces from the current tick and the last tick.
             */
            $lifetime *= 2;

            $this->setLifetime($lifetime);

            add_filter('nonce_life', array($this, 'nonceLifetime'));
        }
    }

    /**
     * Hooks into the nonce_life filter.
     *
     * @param int $oldLifetime The old lifetime.
     *
     * @return int $lifetime     The lifetime.
     **/
    public function nonceLifetime(int $oldLifetime): int
    {
        return $this->getLifetime(false);
    }
}