<?php

namespace FeatureFlag\DataSources;

use FeatureFlag\Contracts\DataSourceInterface;
use FeatureFlag\Exceptions\Exception;
use FeatureFlag\Exceptions\FlagNotFound;
use FeatureFlag\Traits\CastValue;

class SimpleObject implements DataSourceInterface {

    use CastValue;

    /**
     * @var \stdClass
     */
    protected $list;

    /**
     * SimpleJson constructor.
     * @param \stdClass $list
     * @throws Exception
     */
    public function __construct(\stdClass $list) {
        $this->processList($list);
    }

    /**
     * @param \stdClass $list
     * @return DataSourceInterface
     * @throws \FeatureFlag\Exceptions\ValueIsNotCastable
     */
    protected function processList(\stdClass $list): DataSourceInterface {
        $this->list = new \stdClass();
        foreach ($list as $flagName => $value) {
            $this->list->{$flagName} = $this->castValueToBoolean($value);
        }
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get(string $flagName): bool {
        if (!$this->exists($flagName)) {
            throw new FlagNotFound($flagName);
        }
        return $this->list->{$flagName};
    }

    /**
     * @inheritDoc
     */
    public function exists(string $flagName): bool {
        return property_exists($this->list, $flagName);
    }

    /**
     * @inheritDoc
     */
    public function all(): array {
        return (array)$this->list;
    }

}