<?php

namespace FeatureFlag\DataSources;

use FeatureFlag\Contracts\DataSourceInterface;
use FeatureFlag\Exceptions\DataSourceException;
use FeatureFlag\Exceptions\DataSourceProcessingError;
use FeatureFlag\Exceptions\Exception;

class RemoteJsonCurl extends SimpleObject {

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var string|null
     */
    protected $pathInObject;

    /**
     * @var bool
     */
    protected $isDataFetched = false;

    /**
     * RemoteJsonCurl constructor.
     * @param string $url
     * @param array $headers
     * @param string|null $pathInObject
     */
    public function __construct(string $url, array $headers = [], string $pathInObject = null) {
        $this->url = $url;
        $this->headers = $headers;
        $this->pathInObject = $pathInObject;
    }

    /**
     * @return DataSourceInterface
     * @throws DataSourceException
     * @throws Exception
     */
    protected function fetchData(): DataSourceInterface {
        $data = $this->getContent($this->url, $this->headers);
        if ($this->pathInObject) {
            $pathAsArray = explode('.', $this->pathInObject);
            $data = $this->getListInObject($data, $pathAsArray);
        }
        $this->processList($data);
        $this->isDataFetched = true;
        return $this;
    }

    /**
     * @param \stdClass $list
     * @param array|null $path
     * @return \stdClass
     * @throws DataSourceException
     */
    protected function getListInObject(\stdClass $list, array $path = null) {
        if ($path === null || empty($path)) {
            return $list;
        }
        $key = array_shift($path);
        if (!property_exists($list, $key)) {
            throw new DataSourceProcessingError('Path not found in object');
        }
        if (!$list->{$key} instanceof \stdClass) {
            throw new DataSourceProcessingError('Invalid list type');
        }
        return $this->getListInObject($list->{$key}, $path);
    }

    /**
     * @param string $url
     * @param array $headers
     * @return bool|string
     */
    protected function getServerResponse(string $url, array $headers) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $apiResponse = curl_exec($ch);
        curl_close($ch);
        return $apiResponse;
    }

    /**
     * @param string $url
     * @param array $headers
     * @return \stdClass
     * @throws DataSourceException
     */
    protected function getContent(string $url, array $headers): \stdClass {
        $apiResponse = $this->getServerResponse($url, $headers);
        $result = json_decode($apiResponse);
        if (in_array($result, [true, false, null], true)) {
            throw new DataSourceException("Invalid JSON response from data source");
        }
        return $result;
    }

    /**
     * @param string $flagName
     * @return bool
     * @throws DataSourceException
     * @throws Exception
     * @throws \FeatureFlag\Exceptions\FlagNotFound
     */
    public function get(string $flagName): bool {
        if (!$this->isDataFetched) {
            $this->fetchData();
        }
        return parent::get($flagName);
    }

    /**
     * @param string $flagName
     * @return bool
     * @throws DataSourceException
     * @throws Exception
     */
    public function exists(string $flagName): bool {
        if (!$this->isDataFetched) {
            $this->fetchData();
        }
        return parent::exists($flagName);
    }

    /**
     * @return array
     * @throws DataSourceException
     * @throws Exception
     */
    public function all(): array {
        if (!$this->isDataFetched) {
            $this->fetchData();
        }
        return parent::all();
    }


}