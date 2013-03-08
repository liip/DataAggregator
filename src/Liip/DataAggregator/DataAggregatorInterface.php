<?php

namespace Liip\DataAggregator;

use Liip\DataAggregator\Loaders\LoaderInterface;
use Liip\DataAggregator\Persistors\PersistorInterface;

interface DataAggregatorInterface
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

    /**
     * Executes the processing of every attached loader
     */
    public function run();

    /**
     * Forwards the gathered data to every registered output handler.
     *
     * @param array $data
     */
    public function persist(array $data);
}
