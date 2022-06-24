<?php

namespace NoncesManager\Nonces;

interface CreatableNonce
{

    /**
     * Create a new nonce
     *
     * @link https://codex.wordpress.org/Function_Reference/wp_create_nonce
     *
     * @return void
     **/
    public function create(): void;
}