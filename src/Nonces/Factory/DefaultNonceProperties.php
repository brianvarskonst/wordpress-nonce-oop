<?php

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

interface DefaultNonceProperties
{
    public const ACTION = '-1';

    public const REQUEST_NAME = '_wpnonce';

    public const LIFETIME = DAY_IN_SECONDS;
}
