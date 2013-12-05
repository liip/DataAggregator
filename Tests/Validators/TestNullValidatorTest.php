<?php
namespace Liip\DataAggregator\Tests\Validators;

use Liip\DataAggregator\Validators\NullValidator;

/**
 * Class TestNullValidatorTest
 * @package LiipDataAggregatorTestsValidators
 */
class TestNullValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Liip\DataAggregator\Validators\NullValidator::isValid
     */
    public function testIsValid()
    {
        $validator = new NullValidator();

        $this->assertTrue($validator->isValid('Linus'));
    }

    /**
     * @covers \Liip\DataAggregator\Validators\NullValidator::validate
     */
    public function testValidate()
    {
        $validator = new NullValidator();

        $this->assertNull($validator->validate('Tux'));
    }
}
