<?php

declare(strict_types=1);

namespace NoncesManager;

interface Configuration
{

    public function getAction(): string;

    public function getRequestName(): string;

    public function getLifetime(): ?int;
}