<?php

namespace FeatureFlag\Contracts;

interface WritableSourceInterface {

    /**
     * @param string $flagName
     * @param bool $value
     * @return bool
     */
    public function add(string $flagName, bool $value): bool;

    /**
     * @param string $flagName
     * @param bool $value
     * @return bool
     */
    public function update(string $flagName, bool $value): bool;

    /**
     * @param string $flagName
     * @return bool
     */
    public function delete(string $flagName): bool;

}