<?php

namespace Liip\DataAggregator;

/**
 * Defines the contract every aggregator has to full fill.
 *
 * @package Liip\DataAggregator
 */
interface DataAggregatorInterface
{
    /**
     * Executes the processing of every attached loader
     */
    public function run();
}
