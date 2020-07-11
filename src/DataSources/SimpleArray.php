<?php

namespace FeatureFlag\DataSources;

use FeatureFlag\Contracts\DataSourceInterface;
use FeatureFlag\Exceptions\Exception;
use FeatureFlag\Exceptions\FlagNotFound;
use FeatureFlag\Traits\CastValue;

class SimpleArray implements DataSourceInterface {

    use CastValue;

    /**
     * @var array
     */
    protected $list = [];

    /**
     * SimpleArray constructor.
     * @param array $list
     * @throws Exception
     */
    public function __construct(array $list) {
        foreach ($list as $flagName => $value) {
            if (!is_string($flagName)) {
                throw new Exception("Flag name in config must be a string");
            }
            $value = $this->castValueToBoolean($value);
            $this->list[$flagName] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $flagName): bool {
        if (!$this->exists($flagName)) {
            throw new FlagNotFound($flagName);
        }
        return $this->list[$flagName];
    }

    /**
     * @inheritDoc
     */
    public function exists(string $flagName): bool {
        return array_key_exists($flagName, $this->list);
    }

    /**
     * @inheritDoc
     */
    public function all(): array {
        return $this->list;
    }

}