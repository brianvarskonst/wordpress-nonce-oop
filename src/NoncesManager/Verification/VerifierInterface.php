<?php


namespace NoncesManager\Nonces\Verification;

/**
 * Interface VerifierInterface
 *
 * @package NoncesManager\Nonces\Verification
 */
interface VerifierInterface {
    /**
     * Verify the Nonce with time limit
     *
     * @param string $nonce The nonce in the form to verify
     *
     * @return bool $valid  false if it's invalid or 1 or 2 if it's valid.
     **/
    public function verify(string $nonce = null): bool;

    /**
     * Get the age of an nonce
     *
     * @link https://developer.wordpress.org/reference/functions/wp_verify_nonce
     *
     * @return string $age  false if it's invalid or 1 or 2 if it's valid.
     **/
    public function getAge(): string;

    /**
     * Display expired message to confirm the action being taken.
     * Default Message: 'The link you followed has expired.'
     *
     * @link https://developer.wordpress.org/reference/functions/wp_nonce_ays
     *
     * @param string|null $action The Action to handle (optional)
     */
    public function showMessageHasExpired(string $action = null): void;
}