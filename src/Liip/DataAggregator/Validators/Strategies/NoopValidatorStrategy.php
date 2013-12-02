<?php
namespace Liip\DataAggregator\Validators\Strategies;

/**
 * Class NoopValidatorStrategy
 * @package LiipDataAggregatorValidatorsStrategies
 */
class NoopValidatorStrategy implements ValidatorStrategyInterface
{
    /**
     * Invokes the strategy.
     */
    public function execute()
    {
        // intentionally left blank ;)
    }
}
