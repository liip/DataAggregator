<?php
namespace Liip\DataAggregator\Loaders;

/**
 * Introduces the ability to set a maximum amount of data records to be returned.
 *
 * @package Liip\DataAggregator\Loaders
 */
interface LoaderBatchInterface
{
    /**
     * Starts the data loading process.
     *
     * @param integer $limit
     * @param integer $offset
     */
    public function load($limit, $offset = 0);

    /**
     * Backport to the DataAggregator.
     *
     * This backport shall enable the DataAggregator to decide if to stop
     * over other registered loaders.
     *
     * @return boolean
     */
    public function stopPropagation();
}
