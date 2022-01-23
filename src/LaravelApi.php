<?php

namespace LaravelApi\LaravelApi;

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
        return new ($this->getApiWrapper()->faker);
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
