<?php

namespace FeatureFlag\Exceptions;

use Throwable;

class ValueIsNotCastable extends DataSourceException {

    /**
     * ValueIsNotCastable constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Flag value can't be casted to boolean type", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}

