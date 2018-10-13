<?php

namespace App\Http\Modules\Generator;

use Interfaces\CodeGenerator;

class Code implements CodeGenerator
{

    public const DASH_SEPARATOR = '-';
    public const COMMA_SEPARATOR = ',';

    /**
     * Concatenate the values from the given attribute
     *
     * @param string $delimiter
     * @param array $args
     * @return string
     */
    public function make(array $args, string $delimiter)
    {
        return implode($delimiter, $args);
    }
}