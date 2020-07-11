<?php

namespace FeatureFlag\Exceptions;

use Throwable;

class DataSourceProcessingError extends DataSourceException {

    /**
     * DataSourceProcessingError constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "Data source processing exception", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}