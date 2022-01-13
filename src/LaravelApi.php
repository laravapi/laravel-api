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
        $apiManifest = include app()->bootstrapPath('cache/laravel-api-manifest.php');

        return $apiManifest[$this->api]['definition'];
    }
}
