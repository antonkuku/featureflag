<?php

namespace tests\Unit\DataSources;

use FeatureFlag\DataSources\SimpleObject;
use FeatureFlag\Exceptions\Exception;
use FeatureFlag\Exceptions\FlagNotFound;
use tests\TestCase;

class SimpleObjectTest extends TestCase {

    /**
     * @throws Exception
     * @throws FlagNotFound
     */
    public function testInit() {
        $config = new \stdClass();
        $config->first_flag = true;
        $config->second_flag = false;
        $config->third_flag = 1;

        $ds = new SimpleObject($config);

        $this->assertTrue($ds->exists('first_flag'));
        $this->assertTrue($ds->get('first_flag'));

        $this->assertTrue($ds->exists('second_flag'));
        $this->assertFalse($ds->get('second_flag'));

        $this->assertTrue($ds->exists('third_flag'));
        $this->assertTrue($ds->get('third_flag'));
    }

    /**
     * @throws FlagNotFound
     * @throws Exception
     */
    public function testFlagNotFound() {
        $config = new \stdClass();
        $ds = new SimpleObject($config);
        $this->expectException(FlagNotFound::class);
        $ds->get('unknown_flag');
    }

    /**
     * @throws Exception
     */
    public function testAll() {
        $config = new \stdClass();
        $config->first_flag = true;
        $config->second_flag = false;
        $config->third_flag = 1;

        $ds = new SimpleObject($config);

        $all = $ds->all();
        $this->assertCount(3, $all);
        $this->assertTrue($all['first_flag']);
        $this->assertFalse($all['second_flag']);
        $this->assertTrue($all['third_flag']);
    }

}