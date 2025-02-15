<?php

namespace IchieBenjamin\CodeGenerator\Models;

use IchieBenjamin\CodeGenerator\Models\Field;

class FieldMapper
{
    /**
     * The field to optimize
     *
     * @var array of IchieBenjamin\CodeGenerator\Models\Field
     */
    public $field;

    /**
     * Create a new optimizer instance.
     *
     * @var array
     */
    public $meta;

    /**
     * Creates a new field instance.
     *
     * @param IchieBenjamin\CodeGenerator\Models\Field $field
     * @param array $meta
     *
     * @return void
     */
    public function __construct(Field $field, array $meta = [])
    {
        $this->field = $field;
        $this->meta = $meta;
    }
}
