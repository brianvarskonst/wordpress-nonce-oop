<?php

declare(strict_types=1);

namespace NoncesManager\Nonces\Types;

use NoncesManager\Nonces\Nonce;

/**
 * Class UrlType
 * Create Nonce Url
 *
 * @package NoncesManager\Nonces\Types
 */
class UrlType extends Nonce implements NonceTypeInterface
{

    /**
     * The URL
     *
     * @access private
     * @var string
     **/
    private $url = '';

    /**
     * Generate a nonce request url
     *
     * @link https://developer.wordpress.org/reference/functions/wp_nonce_url
     *
     * @param string    $url    The URL to append the Nonce.
     *
     * @return string   $url    The generated nonce url
     **/
    public function generate(string $url): string
    {
        if (!$this->check()) {
            $this->create();
        }

        $url = wp_nonce_url($url, $this->getAction(), $this->getRequestName());

        $this->setUrl($url);

        return $this->getUrl();
    }

    /**
     * Set the URL
     * Don't expose this method, only set the url with the generate method
     *
     * @param string    $newUrl     The new URL.
     *
     * @return void
     **/
    protected function setUrl(string $newUrl): void
    {
        $this->url = $newUrl;
    }

    /**
     * Get the URL
     *
     * @return string   $url    The URL
     **/
    public function getUrl(): string
    {
        return $this->url;
    }
}