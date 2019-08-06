<?php

declare(strict_types=1);

namespace NoncesManager\Nonces;

/**
 * Class NonceAbstract
 * Nonce abstract class
 *
 * @package NoncesManager\Nonces
 */
abstract class NonceAbstract implements NonceAbstractInterface
{
    /**
     * The name of the action
     *
     * @access private
     * @var string
     **/
    private $action = '';

    /**
     * The name of the request
     *
     * @access private
     * @var string
     **/
    private $request_name = '';

    /**
     * The nonce
     *
     * @access private
     * @var string
     **/
    private $nonce = '';

    /**
     * The lifetime of a nonce in seconds
     *
     * @access private
     * @var int
     **/
    private $lifetime = DAY_IN_SECONDS;

    /**
     * Set the nonce
     *
     * @param string $nonce The nonce to verify.
     *
     * @return string $nonce    The nonce
     **/
    protected function setNonce(string $nonce): string
    {
        $this->nonce = $nonce;

        return $this->getNonce();
    }

    /**
     * {@inheritDoc}
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * Set the lifetime
     *
     * @param int $lifetime The new lifetime.
     *
     * @return int     $lifetime     The current nonce lifetime
     **/
    protected function setLifetime(int $lifetime): int
    {
        $this->lifetime = $lifetime;

        return $this->getLifetime();
    }

    /**
     * {@inheritDoc}
     */
    public function getLifetime(bool $actualLifetime = true): int
    {
        if ($actualLifetime) {
            /**
             * We run $lifetime through the 'nonce_life' to get the actual lifetime, which
             * the system is using right now, since other systems might interfere with
             * this filter.
             */
            return (int) apply_filters('nonce_life', $this->lifetime);
        }

        return $this->lifetime;
    }

    /**
     * Set the action
     *
     * @param string $action The action name.
     *
     * @return string $action     The action.
     **/
    protected function setAction(string $action): string
    {
        $this->action = $action;

        return $this->getAction();
    }

    /**
     * {@inheritDoc}
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Set the request name
     *
     * @param string $requestName The new request name.
     **/
    protected function setRequestName(string $requestName): void
    {
        $this->request_name = $requestName;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequestName(): string
    {
        return $this->request_name;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string {
        return $this->getNonce();
    }
}