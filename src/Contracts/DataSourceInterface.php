<?php

namespace FeatureFlag\Contracts;

use FeatureFlag\Exceptions\FlagNotFound;

interface DataSourceInterface {

    /**
     * @param string $flagName
     * @return bool
     * @throws FlagNotFound
     */
    public function get(string $flagName): bool;

    /**
     * @param string $flagName
     * @return bool
     */
    public function exists(string $flagName): bool;

    /**
     * List of all flags with values
     * @return array
     */
    public function all(): array;

}