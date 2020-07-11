<?php

namespace FeatureFlag\Traits;

use FeatureFlag\Exceptions\ValueIsNotCastable;

trait CastValue {

    /**
     * @param $value
     * @return bool
     * @throws ValueIsNotCastable
     */
    protected function castValueToBoolean($value): bool {
        if (in_array($value, [true, 'true', 'TRUE', 1, '1'], true)) {
            return true;
        }
        if (in_array($value, [false, 'false', 'FALSE', 0, '0'], true)) {
            return false;
        }

        throw new ValueIsNotCastable();
    }

}