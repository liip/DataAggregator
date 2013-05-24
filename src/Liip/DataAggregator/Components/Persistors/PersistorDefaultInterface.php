<?php
namespace Liip\DataAggregator\Components\Persistors;

use Liip\DataAggregator\Persistors\PersistorInterface;

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
    public function detachPersistor($key = '');
}
