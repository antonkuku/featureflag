<?php

namespace FeatureFlag\DataSources;

use FeatureFlag\Contracts\WritableSourceInterface;

class SimpleArrayWritable extends SimpleArray implements WritableSourceInterface {

    /**
     * @param string $flagName
     * @param bool $value
     * @return bool
     */
    public function add(string $flagName, bool $value): bool {
        if($this->exists($flagName)) {
            return false;
        }
        $this->list[$flagName] = $value;
        return true;
    }

    /**
     * @param string $flagName
     * @param bool $value
     * @return bool
     */
    public function update(string $flagName, bool $value): bool {
        if(!$this->exists($flagName)) {
            return false;
        }
        $this->list[$flagName] = $value;
        return true;
    }

    /**
     * @param string $flagName
     * @return bool
     */
    public function delete(string $flagName): bool {
        if(!$this->exists($flagName)) {
            return false;
        }
        unset($this->list[$flagName]);
        return true;
    }


}