<?php
namespace Liip\DataAggregator\Components\Loaders;


use Liip\DataAggregator\Loaders\LoaderInterface;

/**
 * Default definition of how to attach/detach loaders to an aggregator.
 *
 * @package Liip\DataAggregator\Components\Loaders
 */
interface LoaderDefaultInterface
{
    /**
     * Adds given loader to registry.
     *
     * @param \Liip\DataAggregator\Loaders\LoaderInterface $loader
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
