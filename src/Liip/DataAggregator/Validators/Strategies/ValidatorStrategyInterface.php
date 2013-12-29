<?php

namespace Liip\DataAggregator\Validators\Strategies;

/**
 * Defines the contract for a validator strategy implementation.
 *
 * @package LiipDataAggregatorValidators
 */
interface ValidatorStrategyInterface
{
    /**
     * Invokes the strategy.
     */
    public function execute();
}
