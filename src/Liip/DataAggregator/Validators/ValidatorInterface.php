<?php
namespace Liip\DataAggregator\Validators;

/**
 * Defines the contract for a validator implementation.
 *
 * @package LiipDataAggregatorValidators
 */
interface ValidatorInterface
{
    /**
     * Determines the validity of the provided information.
     *
     * @param mixed $value Data to be validated.
     *
     * @throws ValidatorException
     */
    public function validate($value);

    /**
     * Determines the validity of the provided information.
     *
     * @param mixed $value Data to be validated.
     *
     * @return boolean
     */
    public function isValid($value);
}
