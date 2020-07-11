### Feature flag

Library for easy work with feature flags in PHP.

The library allows to add multiple data sources (in case you want to have config with default values and another configs with overridden values).

#### Data sources

Just for example I've some sources:
 * SimpleArray
 * SimpleObject
 * RemoteJsonCurl
 
To add another data source you need to create a new class and implement \FeatureFlag\Contracts\DataSourceInterface with 3 methods:
 * exists - to know that selected flag exists 
 * get - to get flag value
 * all - to return available flags from data source

#### Examples

Single source
```
// init array of flags with values
$config = ['first_flag' => true, 'second_flag' => false];
// init data source
$ds = new \FeatureFlag\DataSources\SimpleArray($config);
// create instance of service and add data source
$service = new \FeatureFlag\FeatureFlag();
$service->addSource($ds);
// single condition check
if($service->enabled('first_flag')) {
    // do something
}
// multiple flags check
if($service->enabledWithDependencies('first_flag', ['second_flag' => false])) {
    // do something
}
```

Multiple sources
```
// init first data source
$firstDsConfig = ['first_flag' => true, 'second_flag' => false];
$firstDs = new \FeatureFlag\DataSources\SimpleArray($firstDsConfig);
// init second data source
$secondDsConfig = new \stdClass;
$secondDsConfig->first_flag = false;
$secondDs = new \FeatureFlag\DataSources\SimpleObject($secondDsConfig);
// init service
$service = new \FeatureFlag\FeatureFlag([$firstDs, $secondDs]);
//
$service->enabled('second_flag'); // false
$service->enabled('first_flag'); // false
```
