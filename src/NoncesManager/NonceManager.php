<?php


namespace NoncesManager;

use NoncesManager\Nonces\Nonce;
use NoncesManager\Nonces\Types\FieldType;
use NoncesManager\Nonces\Types\UrlType;
use NoncesManager\Nonces\Verification\Verifier;

/**
 * Class NonceManager
 * THe Nonce Manager Class
 *
 * @package NoncesManager
 */
final class NonceManager implements NonceManagerInterface
{
    /**
     * The configuration
     *
     * @access private
     * @var Configuration
     */
    private $configuration;

    /**
     * The Nonce
     *
     * @access private
     * @var Nonce
     */
    private $nonce;

    /**
     * The Nonce Field
     *
     * @access private
     * @var FieldType
     */
    private $field;

    /**
     * The Nonce Url
     *
     * @access private
     * @var UrlType
     */
    private $url;

    /**
     * The Nonce Verifier
     *
     * @access private
     * @var Verifier
     */
    private $verifier;

    /**
     * NonceManager constructor.
     *
     * @param Configuration     $configuration  The Configuration for this instance to use.
     * @param FieldType|null    $fieldType      The FieldType for this instance to use.
     * @param UrlType|null      $urlType        The UrlType for this instance to use.
     * @param Verifier|null     $verifier       The Verifier for this instance to use.
     */
    private function __construct(Configuration $configuration, FieldType $fieldType = null, UrlType $urlType = null, Verifier $verifier = null)
    {
        $this->configuration = $configuration;

        if (!isset($fieldType, $urlType, $verifier)) {
            $this->registerComponents($configuration);
        } else {
            $this->field = $fieldType;
            $this->url = $urlType;
            $this->verifier = $verifier;
        }

        $this->nonce = new Nonce($configuration);
    }

    /**
     * Create method for NonceManager instances with injected Dependencies
     *
     * @param Configuration $configuration  The Configuration for this instance to use.
     * @param FieldType     $fieldType      The FieldType for this instance to use.
     * @param UrlType       $urlType        The UrlType for this instance to use.
     * @param Verifier      $verifier       The Verifier for this instance to use.
     *
     * @return NonceManager
     */
    public static function create(Configuration $configuration, FieldType $fieldType, UrlType $urlType, Verifier $verifier): NonceManager
    {
        return new NonceManager($configuration, $fieldType, $urlType, $verifier);
    }

    /**
     * Build method for NonceManager instances with none injected Dependencies through registerComponents()
     *
     * @param Configuration     $configuration The Configuration for this instance to use.
     *
     * @return NonceManager
     */
    public static function build(Configuration $configuration): NonceManager {
        return new NonceManager($configuration);
    }

    /**
     * @param Configuration     $configuration  The Configuration for the new instances to use.
     */
    private function registerComponents(Configuration $configuration): void
    {
        $this->field = new FieldType($configuration);
        $this->url = new UrlType($configuration);
        $this->verifier = new Verifier($configuration);
    }

    /**
     * {@inheritDoc}
     */
    public function Configuration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function Nonce(): Nonce {
        return $this->nonce;
    }

    /**
     * {@inheritDoc}
     */
    public function Field(): FieldType
    {
        return $this->field;
    }

    /**
     * {@inheritDoc}
     */
    public function Url(): UrlType
    {
        return $this->url;
    }

    /**
     * {@inheritDoc}
     */
    public function Verifier(): Verifier
    {
        return $this->verifier;
    }
}