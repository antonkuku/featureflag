<?php

namespace FeatureFlag\Contracts;

use FeatureFlag\Exceptions\Exception;
use FeatureFlag\Exceptions\FlagNotFound;
use FeatureFlag\Exceptions\InvalidDefaultValue;

interface FeatureFlagInterface {

    /**
     * @param DataSourceInterface $source
     * @param bool $addFirst add source with the highest priority
     * @return FeatureFlagInterface
     */
    public function addSource(DataSourceInterface $source, bool $addFirst = true): FeatureFlagInterface;

    /**
     * @param string $flagName
     * @return bool
     */
    public function exists(string $flagName): bool;

    /**
     * @param string $flagName
     * @return bool
     * @throws FlagNotFound
     */
    public function enabled(string $flagName): bool;

    /**
     * True if target flag is enabled and dependencies with the same values
     * @param string $flagName
     * @param array $dependencies flagName=>boolVal
     * @return bool
     * @throws Exception
     */
    public function enabledWithDependencies(string $flagName, array $dependencies): bool;

    /**
     * List of all flags with values
     * @return array
     */
    public function all(): array;

    /**
     * true/false - default value if flag is not found
     * if null - throw FlagNotFound exception
     * @param bool|null $value
     * @return FeatureFlagInterface
     * @throws FlagNotFound
     * @throws InvalidDefaultValue
     */
    public function setDefaultValue($value): FeatureFlagInterface;

}