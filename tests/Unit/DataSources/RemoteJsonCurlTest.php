<?php

namespace tests\Unit\DataSources;

use FeatureFlag\DataSources\RemoteJsonCurl;
use FeatureFlag\Exceptions\DataSourceException;
use FeatureFlag\Exceptions\DataSourceProcessingError;
use tests\TestCase;

class RemoteJsonCurlTest extends TestCase {

    /**
     * @throws DataSourceException
     * @throws \FeatureFlag\Exceptions\Exception
     */
    public function testRealCall() {
        $service = new RemoteJsonCurl('https://localhost/flags');
        $this->expectException(DataSourceException::class);
        $service->exists('first_flag');
    }

    /**
     *
     */
    public function testExists() {
        $mock = \Mockery::mock(RemoteJsonCurl::class, ['https://localhost/flags'])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getServerResponse')
            ->andReturn('{"first_flag": true}');
        $mock->makePartial();
        $this->assertTrue($mock->exists('first_flag'));
        $this->assertTrue($mock->get('first_flag'));
    }

    /**
     *
     */
    public function testGet() {
        $mock = \Mockery::mock(RemoteJsonCurl::class, ['https://localhost/flags'])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getServerResponse')
            ->andReturn('{"first_flag": true}');
        $mock->makePartial();
        $this->assertTrue($mock->get('first_flag'));
    }

    /**
     *
     */
    public function testAll() {
        $mock = \Mockery::mock(RemoteJsonCurl::class, ['https://localhost/flags'])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getServerResponse')
            ->andReturn('{"first_flag": true}');
        $mock->makePartial();
        $all = $mock->all();
        $this->assertNotEmpty($all);
    }

    /**
     *
     */
    public function testNestedObject() {
        $mock = \Mockery::mock(RemoteJsonCurl::class, ['https://localhost/flags', [], 'data.list'])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getServerResponse')
            ->andReturn('{"data": {"list": {"first_flag": true}}}');
        $mock->makePartial();
        $all = $mock->all();
        $this->assertNotEmpty($all);
    }

    /**
     *
     */
    public function testNestedObjectWrongPath() {
        $mock = \Mockery::mock(RemoteJsonCurl::class, ['https://localhost/flags', [], 'data.list.flags'])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getServerResponse')
            ->andReturn('{"data": {"list": {"first_flag": true}}}');
        $mock->makePartial();
        $this->expectException(DataSourceProcessingError::class);
        $mock->all();
    }

    /**
     *
     */
    public function testNestedObjectWrongType() {
        $mock = \Mockery::mock(RemoteJsonCurl::class, ['https://localhost/flags', [], 'data.list'])
            ->shouldAllowMockingProtectedMethods();
        $mock->shouldReceive('getServerResponse')
            ->andReturn('{"data": {"list": "not list"}}');
        $mock->makePartial();
        $this->expectException(DataSourceProcessingError::class);
        $mock->all();
    }

}