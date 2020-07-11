<?php

namespace FeatureFlag\Exceptions;

use Throwable;

class DataSourceException extends Exception {

    /**
     * DataSourceException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Data source exception", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}