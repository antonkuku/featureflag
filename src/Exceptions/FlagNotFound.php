<?php

namespace FeatureFlag\Exceptions;

use Throwable;

class FlagNotFound extends Exception {

    /**
     * FlagNotFound constructor.
     * @param string $flagName
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($flagName = "", $code = 0, Throwable $previous = null) {
        $message = "$flagName flag not found in any source";
        parent::__construct($message, $code, $previous);
    }

}