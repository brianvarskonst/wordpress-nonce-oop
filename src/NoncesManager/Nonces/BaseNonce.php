<?php

declare(strict_types=1);

namespace NoncesManager\Nonces;

class BaseNonce extends AbstractBaseNonce implements CreatableNonce
{

    public function create(): void
    {
        $this->setNonce(
            wp_create_nonce(
                $this->getAction()
            )
        );
    }

    public function check(): bool
    {
        return !empty($this->getNonce());
    }
}