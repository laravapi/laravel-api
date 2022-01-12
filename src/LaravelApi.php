<?php

namespace LaravelApi\LaravelApi;

class LaravelApi
{
    protected $definition;

    public function __construct(protected $api)
    {
        $this->definition = new ($this->getAvailableServices()[$this->api]);

        foreach($this->definition->config() as $envKey => $configKey) {
            config([$configKey => $envKey]);
        }

        dd(config('services.twitter'));
    }

    public function __call($name, $arguments)
    {
        return $this->definition->$name(...$arguments);
    }

    private function getAvailableServices()
    {
        return [
            'twitter' => 'Laravapi\Twitter\TwitterWrapper',
        ];
    }
}
