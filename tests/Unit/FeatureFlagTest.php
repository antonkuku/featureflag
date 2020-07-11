<?php

namespace tests\Unit;

use FeatureFlag\DataSources\SimpleArray;
use FeatureFlag\Exceptions\FlagNotFound;
use FeatureFlag\Exceptions\InvalidDefaultValue;
use FeatureFlag\FeatureFlag;
use tests\TestCase;

class FeatureFlagTest extends TestCase {

    /**
     * @throws \FeatureFlag\Exceptions\Exception
     */
    public function testInit(): void {
        $firstFlags = [
            'first_flag' => false,
            'second_flag' => false,
        ];
        $secondFlags = [
            'first_flag' => true,
            'third_flag' => true,
        ];
        $firstDs = new SimpleArray($firstFlags);
        $secondDs = new SimpleArray($secondFlags);
        $flagService = new FeatureFlag([$firstDs, $secondDs]);

        $this->assertEquals(3, count($flagService->all()));
        $this->assertTrue($flagService->enabled('first_flag'));
        $this->assertFalse($flagService->enabled('second_flag'));
        $this->assertTrue($flagService->enabled('third_flag'));
    }

    /**
     * @throws FlagNotFound
     * @throws \FeatureFlag\Exceptions\Exception
     */
    public function testAddSource(): void {
        $firstFlags = [
            'first_flag' => false,
            'second_flag' => true,
        ];
        $secondFlags = [
            'first_flag' => true,
            'third_flag' => true,
        ];
        $firstDs = new SimpleArray($firstFlags);
        $secondDs = new SimpleArray($secondFlags);
        $flagService = new FeatureFlag();

        $flagService->addSource($firstDs);
        $this->assertTrue($flagService->exists('first_flag'));
        $this->assertFalse($flagService->enabled('first_flag'));
        $this->assertFalse($flagService->exists('third_flag'));

        $flagService->addSource($secondDs);
        $this->assertTrue($flagService->exists('first_flag'));
        $this->assertTrue($flagService->enabled('first_flag'));
        $this->assertTrue($flagService->exists('third_flag'));
    }

    /**
     * @throws FlagNotFound
     * @throws \FeatureFlag\Exceptions\Exception
     */
    public function testAddSourceReversive(): void {
        $firstFlags = [
            'first_flag' => false,
        ];
        $secondFlags = [
            'first_flag' => true,
        ];
        $firstDs = new SimpleArray($firstFlags);
        $secondDs = new SimpleArray($secondFlags);
        $flagService = new FeatureFlag();

        $flagService->addSource($firstDs);
        $this->assertTrue($flagService->exists('first_flag'));
        $this->assertFalse($flagService->enabled('first_flag'));

        $flagService->addSource($secondDs, false);
        $this->assertFalse($flagService->enabled('first_flag'));
    }

    /**
     * @throws FlagNotFound
     */
    public function testFlagNotFound(): void {
        $flagService = new FeatureFlag();
        $this->expectException(FlagNotFound::class);
        $flagService->enabled('first_flag');
    }

    /**
     * @throws FlagNotFound
     * @throws \FeatureFlag\Exceptions\InvalidDefaultValue
     */
    public function testDefaultFlagValue(): void {
        $flagService = new FeatureFlag();

        $flagService->setDefaultValue(true);
        $this->assertTrue($flagService->enabled('first_flag'));

        $flagService->setDefaultValue(false);
        $this->assertFalse($flagService->enabled('first_flag'));

        $this->expectException(FlagNotFound::class);
        $flagService->setDefaultValue(null);
        $this->expectException(FlagNotFound::class);
        $flagService->enabled('first_flag');
    }

    /**
     * @throws FlagNotFound
     * @throws InvalidDefaultValue
     */
    public function testSetDefaultFlagInvalidType(): void {
        $flagService = new FeatureFlag();
        $this->expectException(InvalidDefaultValue::class);
        $flagService->setDefaultValue([]);
    }

    /**
     * @throws \FeatureFlag\Exceptions\Exception
     */
    public function testEnabledWithDependencies(): void {
        $flags = [
            'first_flag' => true,
            'second_flag' => false,
            'third_flag' => true,
        ];
        $ds = new SimpleArray($flags);

        $flagService = new FeatureFlag([$ds]);

        $this->assertFalse(
            $flagService->enabledWithDependencies('first_flag', ['second_flag' => true])
        );

        $this->assertTrue(
            $flagService->enabledWithDependencies('first_flag', ['second_flag' => false])
        );

        $this->assertFalse(
            $flagService->enabledWithDependencies('second_flag', ['first_flag' => true])
        );

        $this->assertTrue(
            $flagService->enabledWithDependencies('first_flag', [
                'second_flag' => false,
                'third_flag' => true
            ])
        );

        $this->assertTrue(
            $flagService->enabledWithDependencies('first_flag', ['second_flag' => 0])
        );

    }

}