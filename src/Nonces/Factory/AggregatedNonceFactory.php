<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use InvalidArgumentException;

class AggregatedNonceFactory implements NonceFactory
{
    private array $types;

    /**
     * @var NonceFactory[]
     */
    private array $childFactories;

    public function __construct(array $types, NonceFactory ...$childFactories)
    {
        $this->types = $types;
        $this->childFactories = $childFactories;
    }

    public function accepts(string $type, array $data): bool
    {
        return in_array($type, $this->types, true);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function create(string $type, array $data): Nonce
    {
        foreach ($this->childFactories as $factory) {
            if ($factory->accepts($type, $data)) {
                return $factory->create($type, $data);
            }
        }

        throw new InvalidArgumentException(
            "Invalid type or data was provided, no supported factory could be found."
        );
    }

    public function getSupportedTypes(): array
    {
        return $this->types;
    }
}