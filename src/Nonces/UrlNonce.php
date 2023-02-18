<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces;

class UrlNonce extends SimpleNonce
{
    /**
     * @param string $url The URL to append the Nonce.
     */
    public function __construct(
        private string $url,
        string $action,
        string $requestName,
        int $lifetime = DAY_IN_SECONDS
    ) {

        parent::__construct($action, $requestName, $lifetime);
    }

    /**
     * Generate a nonce request url
     *
     * @link https://developer.wordpress.org/reference/functions/wp_nonce_url
     **/
    public function generate(): Nonce
    {
        if (!$this->check()) {
            $this->refresh();
        }

        $this->url = wp_nonce_url(
            $this->url,
            $this->action,
            $this->requestName
        );

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
