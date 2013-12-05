<?php
namespace Liip\DataAggregator\Validators;

/**
 * Class ValidatorAbstract
 * @package LiipDataAggregatorValidators
 */
abstract class ValidatorAbstract implements ValidatorInterface
{
    /**
     * Determines the validity of the provided information.
     *
     * Since this is a no operation validator the result is always positive.
     *
     * @param mixed $value Data to be validated.
     *
     * @return true
     */
    public function isValid($value)
    {
        try {
            $this->validate($value);

        } catch (\InvalidArgumentException $e) {

            return false;
        }

        return true;
    }
}
