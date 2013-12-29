<?php
namespace Liip\DataAggregator\Validators\Strategies;

use Liip\DataAggregator\Validators\ValidatorInterface;

/**
 * Class MultiValidatorStrategy
 * @package LiipDataAggregatorValidators
 */
abstract class MultiValidatorStrategy implements ValidatorStrategyInterface
{
    /**
     * Adds a validator to the strategy to be invoked on a specific variable type.
     *
     * @param string $type Validation to be performed on the specific variable type
     * @param ValidatorInterface $validator
     */
    abstract public function attach($type, ValidatorInterface $validator);

    /**
     * Removes a validator from the strategy.
     *
     * @param string $type
     */
    abstract public function detach($type);
}
