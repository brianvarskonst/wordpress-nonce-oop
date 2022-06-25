<?php

namespace Bvsk\WordPress\NonceManager\Verification;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;

/**
 * Interface VerifierInterface
 *
 * @package NoncesManager\Nonces\Verification
 */
interface Verifier
{
    /**
     * Verify the Nonce with time limit
     *
     * @return bool $valid  false if it's invalid or 1 or 2 if it's valid.
     **/
    public function verify(Nonce $nonce): bool;

    /**
     * Get the age of an nonce
     *
     * @link https://developer.wordpress.org/reference/functions/wp_verify_nonce
     *
     * @return string $age  false if it's invalid or 1 or 2 if it's valid.
     **/
    public function getAge(Nonce $nonce): string;

    /**
     * Display expired message to confirm the action being taken.
     * Default Message: 'The link you followed has expired.'
     *
     * @link https://developer.wordpress.org/reference/functions/wp_nonce_ays
     */
    public function renderMessageHasExpired(Nonce $nonce): void;
}
