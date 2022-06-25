<?php

declare(strict_types=1);

namespace NoncesManager\Nonces;

class FieldNonce extends SimpleNonce
{
    private bool $referer;

    private ?string $field = null;

    /**
     * @param boolean $referer Sets the referer field for validation.
     */
    public function __construct(
        string $action,
        string $requestName,
        int $lifetime = DAY_IN_SECONDS,
        bool $referer = false
    ) {
        parent::__construct($action, $requestName, $lifetime);

        $this->referer = $referer;
    }

    /**
     * Generate a hidden nonce input field for forms
     *
     * @link https://developer.wordpress.org/reference/functions/wp_nonce_field
     **/
    public function generate(): Nonce
    {
        if (!$this->check()) {
            $this->refresh();
        }

        $this->field = wp_nonce_field(
            $this->action,
            $this->requestName,
            $this->referer,
            false
        );

        return $this;
    }

    public function render(): void
    {
        echo wp_kses(
            $this->field,
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