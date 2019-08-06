<?php

declare(strict_types=1);

namespace NoncesManager;

use NoncesManager\Nonces\Nonce;
use NoncesManager\Nonces\Types\FieldType;
use NoncesManager\Nonces\Types\UrlType;
use NoncesManager\Nonces\Verification\Verifier;


/**
 * Interface NonceManagerInterface
 *
 * @package NoncesManager
 */
interface NonceManagerInterface
{

    /**
     * Returns the Configuration used by the NonceManager
     *
     * @return Configuration
     */
    public function Configuration(): Configuration;

    /**
     * Returns the nonce create API to create a new nonce
     *
     * @return Nonce
     */
    public function Nonce(): Nonce;

    /**
     * Returns the nonce create field API to create a new nonce field
     *
     * @return FieldType
     */
    public function Field(): FieldType;

    /**
     * Returns the nonce create url API to create a new nonce url
     *
     * @return UrlType
     */
    public function Url(): UrlType;

    /**
     * Returns the nonce verify API to verify the created nonce
     *
     * @return Verifier
     */
    public function Verifier(): Verifier;
}