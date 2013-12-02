<?php
namespace Liip\DataAggregator\Validators;

/**
 * Extends an implementing class with the ability to use validators without changing the footprint of the constructor.
 *
 * @package LiipDataAggregatorValidators
 */
interface ValidatorAwareInterface
{
    /**
     * Defines the validator to be used.
     *
     * @param ValidatorInterface $validator
     *
     */
    public function setValidator(ValidatorInterface $validator);
}
