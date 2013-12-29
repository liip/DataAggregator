<?php
namespace Liip\DataAggregator\Tests\Validators\Strategies;

use Liip\DataAggregator\Validators\Strategies\NoopValidatorStrategy;

/**
 * Class NoopValidatorStrategyTest
 * @package LiipDataAggregatorTestsValidatorsStrategies
 */
class NoopValidatorStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Liip\DataAggregator\Validators\Strategies\NoopValidatorStrategy::execute
     */
    public function testExecute()
    {
        $strategy = new NoopValidatorStrategy();

        $this->assertNull($strategy->execute());
    }
}
