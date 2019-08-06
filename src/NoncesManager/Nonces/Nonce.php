<?php
/**
 * Create Nonce
 */

declare(strict_types=1);

namespace NoncesManager\Nonces;

use NoncesManager\Configuration;

/**
 * Class Nonce
 * Create Nonce
 *
 * @package NoncesManager\Nonces
 */
class Nonce extends NonceAbstract implements NonceInterface
{
    /**
     * Configures the settings
     *
     * @param Configuration $configuration The configuration.
     **/
    public function __construct(Configuration $configuration)
    {
        $this->setAction($configuration->getAction());
        $this->setRequestName($configuration->getRequestName());
        $this->setLifetime($configuration->getLifetime());
    }

    /**
     * {@inheritDoc}
     */
    public function create(): void
    {
        $this->setNonce(wp_create_nonce($this->getAction()));
    }

    /**
     * Check if Nonce is setted or not
     *
     * @return bool     true if is setted | false if isn't setted
     */
    protected function check(): bool {
        $nonce = $this->getNonce();

        return ($nonce !== null || $nonce !== '');
    }
}