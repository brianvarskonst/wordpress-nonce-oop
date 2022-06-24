<?php

namespace NoncesManager\Nonces\Types;

interface RenderableNonceType
{

    public function render(): void;
}