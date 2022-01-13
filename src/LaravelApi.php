<?php

namespace LaravelApi\LaravelApi;

class LaravelApi
{
    protected $apiClient;

    public function __construct(protected $api)
    {
        $this->apiClient = new ($this->getClientClassName());
    }

    public function __call($name, $arguments)
    {
        return $this->apiClient->$name(...$arguments);
    }

    private function getClientClassName()
    {
        return app(ManifestManager::class)->getManifest($this->api);
    }
}
