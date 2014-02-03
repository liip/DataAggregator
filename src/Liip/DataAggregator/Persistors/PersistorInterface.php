<?php

namespace Liip\DataAggregator\Persistors;

/**
 * Defines the contract every persistor has to full fill.
 *
 * @package Liip\DataAggregator\Persistors
 */
interface PersistorInterface
{
    /**
     * Persists provided data.
     *
     * @param array $data
     */
    public function persist(array $data);

    /**
     * Removes provided data.
     *
     * @param array $data
     */
    public function remove(array $data);
}
