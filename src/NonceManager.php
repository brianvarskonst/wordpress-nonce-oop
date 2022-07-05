<?php

declare(strict_types=1);

namespace Bvsk\WordPress\NonceManager;

use Bvsk\WordPress\NonceManager\Nonces\Factory\AggregatedNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Factory\FieldNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Factory\SimpleNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Factory\UrlNonceFactory;
use Bvsk\WordPress\NonceManager\Nonces\Nonce;
use Bvsk\WordPress\NonceManager\Nonces\Factory\NonceFactory;
use Bvsk\WordPress\NonceManager\Verification\NonceVerifier;
use Bvsk\WordPress\NonceManager\Verification\Verifier;

final class NonceManager
{
    private NonceFactory $nonceFactory;

    private Verifier $verifier;

    public function __construct(
        NonceFactory $nonceFactory,
        Verifier $verifier
    ) {

        $this->nonceFactory = $nonceFactory;
        $this->verifier = $verifier;
    }

    public static function createFromDefaults(): NonceManager
    {
        return new NonceManager(
            new AggregatedNonceFactory(
                new SimpleNonceFactory(),
                new FieldNonceFactory(),
                new UrlNonceFactory()
            ),
            new NonceVerifier()
        );
    }

    public function createNonce(string $type, array $data = []): Nonce
    {
        return $this->nonceFactory->create($type, $data);
    }

    public function verify(Nonce $nonce): bool
    {
        return $this->verifier->verify($nonce);
    }
}
