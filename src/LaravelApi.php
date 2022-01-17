<?php

namespace LaravelApi\LaravelApi;

class LaravelApi
{
    protected $wrapper;

    public function __construct(protected $api)
    {
        $this->wrapper = $this->getWrapper();
    }

    public function __call($name, $arguments)
    {
        return $this->wrapper->$name(...$arguments);
    }

    public function fake()
    {
        return new ($this->getWrapper()->faker);
    }

    private function getWrapper()
    {
        return new ($this->getWrapperClassName());
    }

    private function getWrapperClassName()
    {
        return app(ManifestManager::class)->getManifest($this->api);
    }
}
