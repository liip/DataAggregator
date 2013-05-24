<?php
namespace Liip\DataAggregator\Components\Loaders;

use Liip\DataAggregator\Loaders\LoaderBatchInterface as LoaderInterface;

/**
 * Definition of how to attach/detach batchable loaders to an aggregator.
 *
 * @package Liip\DataAggregator\Components\Loaders
 */
interface LoaderBatchInterface
{
    /**
     * Adds given loader to registry.
     *
     * @param \Liip\DataAggregator\Loaders\LoaderBatchInterface $loader
     * @param string $key
     */
    public function attachLoader(LoaderInterface $loader, $key = '');

    /**
     * Removes a loader identified by the given key from the registry.
     *
     * @param string $key
     */
    public function detachLoader($key);
}
