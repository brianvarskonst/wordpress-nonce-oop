<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Verification;

use InvalidArgumentException;
use NoncesManager\Nonces\Nonce;

class NonceVerifier implements Verifier
{
    public function verify(Nonce $nonce): bool
    {
        if ($nonce->getToken() === null) {
            throw new InvalidArgumentException('Invalid Nonce with an empty token provided.');
        }

        return (bool) $this->getAge($nonce);
    }

    public function getAge(Nonce $nonce): string
    {
        return (string) wp_verify_nonce(
            $nonce->getToken(),
            $nonce->getLifetime()
        );
    }

    public function renderMessageHasExpired(Nonce $nonce): void
    {
        wp_nonce_ays($nonce->getAction());
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
     * @return bool false if it's invalid or 1 or 2 if it's valid and returns true.
     */
    public function isAdminReferer(Nonce $nonce): bool
    {
        return (bool) check_admin_referer(
            $nonce->getAction(),
            $nonce->getRequestName()
        );
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
     * @return bool  false if it's invalid or 1 or 2 if it's valid and return true.
     */
    public function isAjaxReferer(Nonce $nonce, bool $die = false): bool
    {
        return (bool) check_ajax_referer(
            $nonce->getAction(),
            $nonce->getRequestName(),
            $die
        );
    }
}
