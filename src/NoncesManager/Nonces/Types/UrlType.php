<?php

declare(strict_types=1);

namespace NoncesManager\Nonces\Types;

use NoncesManager\Nonces\Nonce;

class UrlType extends AbstractNonceType
{
    private string $url;

    /**
     * @param string $url The URL to append the Nonce.
     */
    public function __construct(Nonce $nonce, string $url)
    {
        $this->url = $url;

        parent::__construct($nonce);
    }

    /**
     * Generate a nonce request url
     *
     * @link https://developer.wordpress.org/reference/functions/wp_nonce_url
     **/
    public function generate(): string
    {
        if (!$this->nonce->check()) {
            $this->nonce->create();
        }

        $url = wp_nonce_url(
            $this->url,
            $this->nonce->getAction(),
            $this->nonce->getRequestName()
        );

        $this->url = $url;

        return $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}