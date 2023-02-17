<?php

// phpcs:ignoreFile

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Config;

use ValueError;

enum DefaultWordPressNonceConfig: string
{
case ACTION = 'action';
case REQUEST_NAME = 'requestName';

case LIFETIME = 'lifetime';

case URL = 'url';

case REFERER = 'referer';

    /**
     * @throws ValueError
     */
    public function getDefault(): string|int
    {
        return match ($this) {
            self::ACTION => '-1',
            self::REQUEST_NAME => '_wpnonce',
            self::LIFETIME => DAY_IN_SECONDS,
            self::URL, self::REFERER => throw new ValueError('No default value are existing.'),
        };
    }
    }
