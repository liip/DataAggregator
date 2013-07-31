<?php

namespace Liip\DataAggregator;

/**
 * Defines the contract every aggregator has to full fill.
 *
 * @package Liip\DataAggregator
 */
interface DataAggregatorBatchInterface
{
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

    /**
     * Defines the max amount ot records to be returned by the loader.
     *
     * @param integer $limit
     */
    public function setLimit($limit);

    /**
     * Returns the set amount of max returned records.
     *
     * @return integer
     */
    public function getLimit();
}
