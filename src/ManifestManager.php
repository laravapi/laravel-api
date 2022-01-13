<?php

namespace LaravelApi\LaravelApi;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class ManifestManager
{
    public function __construct(protected $manifestPath) {}

    public function getManifest($api = null)
    {
        if(!File::exists($this->manifestPath)) {
            return [];
        }

        $manifest = include $this->manifestPath;

        return $api ? Arr::get($manifest, $api) : $manifest;
    }

    public function putManifest(array $manifest)
    {
        File::put($this->manifestPath, '<?php return ' . var_export($manifest, true) . ';', true);
    }

    public function add($api, $definition)
    {
        $manifest = $this->getManifest();
        $manifest[$api] = $definition;
        $this->putManifest($manifest);
    }
}
