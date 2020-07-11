<?php

namespace tests\Unit\DataSources;

use FeatureFlag\DataSources\SimpleArray;
use FeatureFlag\Exceptions\Exception;
use FeatureFlag\Exceptions\FlagNotFound;
use tests\TestCase;

class SimpleArrayTest extends TestCase {

    /**
     * @throws Exception
     */
    public function testSuccessful() {
        $flags = [
            'first_flag' => true,
        ];
        $ds = new SimpleArray($flags);
        $this->assertTrue($ds->exists('first_flag'));
        $this->assertFalse($ds->exists('second_flag'));
    }

    /**
     * @return array
     */
    public function initFailDataProvider(): array {
        return [
            [
                ['flag' => [],]
            ],
            [
                ['flag' => new \stdClass()]
            ],
            [
                ['flag' => 15]
            ],
            [
                [12 => true]
            ]
        ];
    }

    /**
     * @dataProvider initFailDataProvider
     * @param array $flags
     * @throws Exception
     */
    public function testInitFail(array $flags) {
        $this->expectException(Exception::class);
        new SimpleArray($flags);
    }

    /**
     * @throws Exception
     */
    public function testCastToTrue() {
        $list = [
            'a' => true,
            'b' => 'true',
            'c' => 'TRUE',
            'd' => 1,
            'e' => '1',
        ];
        $ds = new SimpleArray($list);
        foreach ($list as $flagName => $value) {
            $this->assertTrue($ds->exists($flagName));
            $this->assertTrue($ds->get($flagName));
        }
    }

    /**
     * @throws Exception
     */
    public function testCastToFalse() {
        $list = [
            'a' => false,
            'b' => 'false',
            'c' => 'FALSE',
            'd' => 0,
            'e' => '0',
        ];
        $ds = new SimpleArray($list);
        foreach ($list as $flagName => $value) {
            $this->assertTrue($ds->exists($flagName));
            $this->assertFalse($ds->get($flagName));
        }
    }

    /**
     * @throws Exception
     */
    public function testAll() {
        $list = [
            'a' => true,
            'b' => 1,
            'c' => false,
            'd' => 0
        ];
        $ds = new SimpleArray($list);
        $all = $ds->all();
        $this->assertEquals(count($list), count($all));
        foreach($list as $flagName => $value) {
            $expected = (bool)$list[$flagName];
            $this->assertEquals($expected, $value);
        }
    }

    /**
     * @throws Exception
     */
    public function testFlagNotFound() {
        $list = [];
        $ds = new SimpleArray($list);
        $this->expectException(FlagNotFound::class);
        $ds->get('unknown_flag');
    }

}