<?php
namespace Liip\DataAggregator\Tests\Validators;

/**
 * Class ValidatorAbstractTest
 * @package LiipDataAggregatorTestsValidators
 */
class ValidatorAbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Liip\DataAggregator\Validators\NullValidator::validate
     */
    public function testIsInValid()
    {
        $validator = $this->getMockBuilder('\Liip\DataAggregator\Validators\ValidatorAbstract')
            ->setMethods(array('validate'))
            ->getMockForAbstractClass();
        $validator
            ->expects($this->once())
            ->method('validate')
            ->will($this->throwException(new \InvalidArgumentException));

        $this->assertFalse($validator->isValid('Tux'));
    }

    /**
     * @covers \Liip\DataAggregator\Validators\NullValidator::validate
     */
    public function testIsValid()
    {
        $validator = $this->getMockBuilder('\Liip\DataAggregator\Validators\ValidatorAbstract')
            ->setMethods(array('validate'))
            ->getMockForAbstractClass();
        $validator
            ->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(null));

        $validator->isValid('Tux');
    }
}
