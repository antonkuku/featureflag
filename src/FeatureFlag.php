<?php

namespace FeatureFlag;

use FeatureFlag\Contracts\DataSourceInterface;
use FeatureFlag\Contracts\FeatureFlagInterface;
use FeatureFlag\Exceptions\FlagNotFound;
use FeatureFlag\Exceptions\InvalidDefaultValue;
use FeatureFlag\Traits\CastValue;

class FeatureFlag implements FeatureFlagInterface {

    use CastValue;

    /**
     * @var DataSourceInterface[]
     */
    protected $sources = [];

    /**
     * @var null|bool
     */
    protected $defaultFlagValue = null;

    /**
     * FeatureFlag constructor.
     * @param DataSourceInterface[] $sources
     */
    public function __construct(array $sources = []) {
        foreach ($sources as $source) {
            $this->addSource($source);
        }
    }

    /**
     * @inheritDoc
     */
    public function addSource(DataSourceInterface $source, bool $addFirst = true): FeatureFlagInterface {
        $addFirst ? array_unshift($this->sources, $source) : $this->sources[] = $source;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function exists(string $flagName): bool {
        foreach ($this->sources as $source) {
            if ($source->exists($flagName)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritDoc
     */
    public function enabled(string $flagName): bool {
        foreach ($this->sources as $source) {
            if ($source->exists($flagName)) {
                return $source->get($flagName);
            }
        }

        if ($this->defaultFlagValue === null) {
            throw new FlagNotFound($flagName);
        } else {
            return $this->defaultFlagValue;
        }
    }

    /**
     * @inheritDoc
     */
    public function enabledWithDependencies(string $flagName, array $dependencies): bool {
        if (!$this->enabled($flagName)) {
            return false;
        }
        foreach ($dependencies as $depFlagName => $depFlagValue) {
            $depFlagValue = $this->castValueToBoolean($depFlagValue);
            $value = $this->enabled($depFlagName);
            if ($value !== $depFlagValue) {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function all(): array {
        $result = [];
        foreach ($this->sources as $source) {
            $all = $source->all();
            foreach ($all as $flagName => $value) {
                $result[$flagName] = $value;
            }
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function setDefaultValue($value): FeatureFlagInterface {
        if (!is_bool($value) && !is_null($value)) {
            throw new InvalidDefaultValue();
        }
        $this->defaultFlagValue = $value;
        return $this;
    }

}