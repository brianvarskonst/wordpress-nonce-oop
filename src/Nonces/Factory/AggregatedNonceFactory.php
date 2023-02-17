<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager\Nonces\Factory;

use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use InvalidArgumentException;

final class AggregatedNonceFactory implements NonceFactory
{
    private readonly array $types;
    
    private readonly array $factories;

    public function __construct(NonceFactory ...$factories)
    {
        $this->factories = $factories;

        $this->types = array_map(
            static fn(NonceFactory $factory): string => $factory->getSupportedType(),
            $factories
        );
    }

    public function accepts(string $type, array $data): bool
    {
        return in_array($type, $this->types, true);
    }

    public function getSupportedType(): string
    {
        return implode(', ', $this->types);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function create(string $type, array $data = []): Nonce
    {
        foreach ($this->factories as $factory) {
            if ($factory->accepts($type, $data)) {
                return $factory->create($type, $data);
            }
        }

        throw new InvalidArgumentException(
            "Invalid type or data was provided, no supported factory could be found."
        );
    }
}
