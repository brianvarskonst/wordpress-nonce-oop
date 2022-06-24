<?php

declare(strict_types=1);

namespace NoncesManager\Nonces\Types;

use NoncesManager\Nonces\Nonce;

class FieldType extends Nonce implements NonceType
{

    /**
     * field
     *
     * @access private
     * @var string
     **/
    private $field = '';

    /**
     * Generate a hidden nonce input field for forms
     *
     * @link https://developer.wordpress.org/reference/functions/wp_nonce_field
     *
     * @param boolean $referer  Sets the referer field for validation.
     * @param boolean $echo     Either it displays the hidden form field or it returns.
     *
     * @return string $field   The generated field - Hidden Input field with nonce as HTML markup
     **/
    public function generate(bool $referer = false, bool $echo = false): string
    {
        if (!$this->check()) {
            $this->create();
        }

        $field = wp_nonce_field($this->getAction(), $this->getRequestName(), $referer, $echo);

        $this->setField($field);

        if ($echo) {
            echo wp_kses(
                $this->getField(),
                array(
                    'input' => array(
                        'type' => array(),
                        'id' => array(),
                        'name' => array(),
                        'value' => array(),
                    ),
                )
            );
        }

        return $this->getField();
    }

    /**
     * Set the new generated Field
     *
     * Don't expose this method, only set the field with the generate method
     *
     * @param string $newField The new field.
     *
     * @return void
     **/
    protected function setField(string $newField): void
    {
        $this->field = $newField;
    }

    /**
     * Get the Field
     *
     * @return string The generated Field
     **/
    public function getField(): string
    {
        return $this->field;
    }

}