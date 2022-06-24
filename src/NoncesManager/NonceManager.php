<?php

declare(strict_types=1);

namespace NoncesManager;

use NoncesManager\Nonces\Nonce;
use NoncesManager\Nonces\Types\FieldType;
use NoncesManager\Nonces\Types\UrlType;
use NoncesManager\Nonces\Verification\Verifier;

final class NonceManager
{
    private ?Nonce $nonce;

    private ?FieldType $field;

    private ?UrlType $url;

    private ?Verifier $verifier;

    private function __construct(
        Nonce $nonce,
        ?FieldType $fieldType,
        ?UrlType $urlType,
        ?Verifier $verifier
    ) {

        $this->nonce = $nonce;
        $this->field = $fieldType;
        $this->url = $urlType;
        $this->verifier = $verifier;
    }

    public function getNonce(): ?Nonce
    {
        return $this->nonce;
    }

    public function getField(): ?FieldType
    {
        return $this->field;
    }

    public function getUrl(): ?UrlType
    {
        return $this->url;
    }

    public function getVerifier(): ?Verifier
    {
        return $this->verifier;
    }
}