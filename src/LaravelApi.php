<?php

namespace LaravelApi\LaravelApi;

use Exception;
use LaravelApi\LaravelApi\Exceptions\LaravelApiException;

class LaravelApi
{
    protected $apiWrapper;

    public function __construct(protected $apiKey)
    {
        $this->apiWrapper = $this->getApiWrapper();
    }

    public function __call(string $methodName, array $arguments)
    {
        return $this->apiWrapper->$methodName(...$arguments);
    }

    public function fake()
    {
        return new ($this->apiWrapper->faker);
    }

    public function check()
    {
        if(!method_exists($this->apiWrapper, 'check')) {
            throw new LaravelApiException('The "' . $this->apiKey . '" API wrapper misses the necessary check() method.');
        }

        try {
            $checkResult = (bool) $this->apiWrapper->check();
        }
        catch(Exception $exception) {
            $checkResult = false;
        }

        return $checkResult;
    }

    private function getApiWrapper()
    {
        return new ($this->getApiWrapperClassName());
    }

    private function getApiWrapperClassName()
    {
        return app(ManifestManager::class)->getManifest($this->apiKey);
    }
}
