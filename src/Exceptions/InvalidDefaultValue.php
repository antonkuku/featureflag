<?php

namespace FeatureFlag\Exceptions;

use Throwable;

class InvalidDefaultValue extends Exception {

    /**
     * InvalidDefaultValue constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Default value must be null or boolean type", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}