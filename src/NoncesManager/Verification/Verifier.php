<?php

declare(strict_types=1);

namespace NoncesManager\Nonces\Verification;

use NoncesManager\Configuration;
use NoncesManager\Nonces\NonceAbstract;

/**
 * Class Verifier
 * The Verifier for Nonce
 *
 * @package NoncesManager\Nonces\Verification
 */
class Verifier extends NonceAbstract implements VerifierInterface
{

    /**
     * Verifier constructor.
     * Configure the class
     *
     * @param Configuration $configuration The configuration instance
     */
    public function __construct(Configuration $configuration)
    {
        $this->setAction($configuration->getAction());
        $this->setRequestName($configuration->getRequestName());
        $this->setLifetime($configuration->getLifetime());

        // If the $_REQUEST is set, we set the nonce here.
        if (isset($_REQUEST[$this->getRequestName()])) {
            $nonce = sanitize_text_field(wp_unslash($_REQUEST[$this->getRequestName()]));
            $this->setNonce($nonce);
        }
    }

    /**
     * {@inheritDoc}
     **/
    public function verify(string $nonce = null): bool
    {
        if ($nonce !== null) {
            $this->setNonce($nonce);
        }

        $valid = $this->getAge();

        return (bool) $valid;
    }

    /**
     * {@inheritDoc}
     **/
    public function getAge(): string
    {
        return (string) wp_verify_nonce($this->getNonce(), $this->getAction());
    }

    /**
     * {@inheritDoc}
     **/
    public function showMessageHasExpired(string $action = null): void
    {
        if ($action !== null) {
            $this->setAction($action);
        }

        wp_nonce_ays($this->getAction());
    }

    /**
     * Verify if nonce that was passed in a Url or from an admin screen
     * - Alternative Verification
     *
     * @link https://developer.wordpress.org/reference/functions/check_admin_referer
     *
     * TODO: Implement - optional $requestArgs to method properties
     * TODO: Implement - RequestArgumentParser as a Helper Parser
     * $requestArgs Optional. -> Inject RequestArgumentParser
     * Key to check for the nonce in `$_REQUEST` (since 2.5). Default '_wpnonce'.
     *
     * @return bool     false if it's invalid or 1 or 2 if it's valid and returns true.
     */
    public function checkAdminReferer(): bool
    {
        return (bool) check_admin_referer($this->getAction(), $this->getRequestName());
    }

    /**
     * Verify if nonce that was passed in an Ajax request
     * - Alternative Verification
     *
     * @link https://developer.wordpress.org/reference/functions/check_ajax_referer
     *
     * TODO: Implement - optional $requestArgs to method properties
     * TODO: Implement - RequestArgumentParser as a Helper Parser
     * $requestArgs Optional. -> Inject RequestArgumentParser
     * Key to check for the nonce in `$_REQUEST` (since 2.5).
     * If false, `$_REQUEST` values will be evaluated for '_ajax_nonce', and '_wpnonce' (in that order). Default false.
     *
     * @param bool $die
     *
     * @return bool  false if it's invalid or 1 or 2 if it's valid and return true.
     */
    public function checkAjaxReferer(bool $die = false): bool
    {
        return (bool) check_ajax_referer($this->getAction(), $this->getRequestName(), $die );
    }
}