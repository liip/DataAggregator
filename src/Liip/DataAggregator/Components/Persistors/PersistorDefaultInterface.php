<?php
namespace Liip\DataAggregator\Components\Persistors;

use Liip\DataAggregator\Persistors\PersistorInterface;

/**
 * Default definition of how to attach/detach persistors to an aggregator.
 *
 * @package Liip\DataAggregator\Components\Persistors
 */
interface PersistorDefaultInterface
{
    /**
     * Adds the given persistor to the collection of output handlers
     *
     * @param \Liip\DataAggregator\Persistors\PersistorInterface $persistor
     * @param string $key
     */
    public function attachPersistor(PersistorInterface $persistor, $key = '');

    /**
     * Removes a persistor identified by the given key from the registry.
     *
     * @param string $key
     */
    public function detachPersistor($key);
}
