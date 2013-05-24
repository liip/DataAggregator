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
     */
    public function load();

    /**
     * Backport to the DataAggregator.
     *
     * This backport shall enable the DataAggregator to decide if to stop
     * over other registered loaders.
     *
     * @return boolean
     */
    public function stopPropagation();

    /**
     * Defines the max amount ot records to be returned by the loader.
     *
     * @param $limit
     */
    public function setLimit($limit);

    /**
     * Returns the set amount of max returned records.
     *
     * @return integer
     */
    public function getLimit();
}
