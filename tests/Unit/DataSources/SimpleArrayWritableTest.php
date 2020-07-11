<?php

namespace tests\Unit\DataSources;

use FeatureFlag\DataSources\SimpleArrayWritable;
use FeatureFlag\Exceptions\Exception;
use FeatureFlag\Exceptions\FlagNotFound;
use tests\TestCase;

class SimpleArrayWritableTest extends TestCase {

    /**
     * @throws Exception
     * @throws FlagNotFound
     */
    public function testAdd() {
        $ds = new SimpleArrayWritable([]);
        $this->assertFalse($ds->exists('first_flag'));
        // add flag and check
        $isAdded = $ds->add('first_flag', true);
        $this->assertTrue($isAdded);
        $this->assertTrue($ds->exists('first_flag'));
        $this->assertTrue($ds->get('first_flag'));
        // try to add the same flag again
        $isAdded = $ds->add('first_flag', false);
        $this->assertFalse($isAdded);
    }

    /**
     * @throws Exception
     * @throws FlagNotFound
     */
    public function testUpdate() {
        $ds = new SimpleArrayWritable(['first_flag' => false]);
        $this->assertTrue($ds->exists('first_flag'));
        $this->assertFalse($ds->get('first_flag'));

        $isUpdated = $ds->update('first_flag', true);
        $this->assertTrue($isUpdated);
        $this->assertTrue($ds->exists('first_flag'));
        $this->assertTrue($ds->get('first_flag'));
    }

    /**
     * @throws Exception
     */
    public function testUpdateMissing() {
        $ds = new SimpleArrayWritable([]);
        $this->assertFalse($ds->exists('first_flag'));

        $isUpdated = $ds->update('first_flag', true);
        $this->assertFalse($isUpdated);
        $this->assertFalse($ds->exists('first_flag'));
    }

    /**
     * @throws Exception
     */
    public function testDelete() {
        $ds = new SimpleArrayWritable(['first_flag' => false]);
        $this->assertTrue($ds->exists('first_flag'));

        $isDeleted = $ds->delete('first_flag');
        $this->assertTrue($isDeleted);
        $this->assertFalse($ds->exists('first_flag'));
    }

    /**
     * @throws Exception
     */
    public function testDeleteMissing() {
        $ds = new SimpleArrayWritable([]);
        $this->assertFalse($ds->exists('first_flag'));

        $isDeleted = $ds->delete('first_flag');
        $this->assertFalse($isDeleted);
    }

}