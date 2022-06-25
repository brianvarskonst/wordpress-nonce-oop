<?php

declare(strict_types=1);

namespace NoncesManager\Nonces\Types;

use NoncesManager\Nonces\Nonce;

class FieldType extends AbstractNonceType implements RenderableNonceType
{
    private bool $referer;

    private ?string $field = null;

    /**
     * @param boolean $referer Sets the referer field for validation.
     */
    public function __construct(Nonce $nonce, bool $referer = false)
    {
        $this->referer = $referer;

        parent::__construct($nonce);
    }

    /**
     * Generate a hidden nonce input field for forms
     *
     * @link https://developer.wordpress.org/reference/functions/wp_nonce_field
     **/
    public function generate(): string
    {
        if (!$this->nonce->check()) {
            $this->nonce->create();
        }

        $field = wp_nonce_field(
            $this->nonce->getAction(),
            $this->nonce->getRequestName(),
            $this->referer,
            false
        );

        $this->field = $field;

        return $this->field;
    }

    public function render(): void
    {
        echo wp_kses(
            $this->getField(),
            array(
                'input' => [
                    'type' => [],
                    'id' => [],
                    'name' => [],
                    'value' => [],
                ],
            )
        );
    }

    public function getField(): string
    {
        return $this->field;
    }
}