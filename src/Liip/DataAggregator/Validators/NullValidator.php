<?php
namespace Liip\DataAggregator\Validators;


class NullValidator implements ValidatorInterface
{
    /**
     * Determines the validity of the provided information.
     *
     * @param mixed $value Data to be validated.
     *
     * @throws ValidatorException
     */
    public function validate($value)
    {
        // intentionally left blank ;)
    }

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
        return true;
    }

}
