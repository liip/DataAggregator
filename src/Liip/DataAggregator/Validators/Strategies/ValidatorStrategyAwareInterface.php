<?php
namespace Liip\DataAggregator\Validators\Strategies;

/**
 * Interface ValidatorStrategyAwareInterface
 *
 * @package LiipDataAggregatorValidatorsStrategies
 */
interface ValidatorStrategyAwareInterface
{
    /**
     * Defines the validator strategy to be use for the impreginanted class.
     *
     * @param ValidatorStrategyInterface $strategy
     */
    public function setValidatorStrategy(ValidatorStrategyInterface $strategy);

}
