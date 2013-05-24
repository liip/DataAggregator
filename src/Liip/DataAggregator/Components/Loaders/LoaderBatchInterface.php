<?php
namespace Liip\DataAggregator\Components\Loaders;

use Liip\DataAggregator\Loaders\LoaderBatchInterface AS Loader;

interface LoaderBatchInterface
{
    /**
     * Adds given loader to registry.
     *
     * @param \Liip\DataAggregator\Loaders\LoaderBatchInterface $loader
     * @param string $key
     */
    public function attachLoader(Loader $loader, $key = '');

    /**
     * Removes a loader identified by the given key from the registry.
     *
     * @param string $key
     */
    public function detachLoader($key);
}
