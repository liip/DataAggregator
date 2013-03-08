<?php
namespace Liip\DataAggregator\Tests;

use Liip\DataAggregator\DataAggregator;
use Liip\DataAggregator\Loaders\LoaderException;

/**
 * Test case to verify the correctness of the DataAggregator implementation
 */
class DataAggregatorTest extends DataAggregatorTestCase
{
    /**
     * @covers \Liip\DataAggregator\DataAggregator::attachLoader
     */
    public function testAttachLoader()
    {
        $loader = $this->getDataLoaderStub();
        $expected = array(0 => $loader);

        $da = new DataAggregator();
        $da->attachLoader($loader);

        $this->assertEquals($expected, $da->loaders);
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::attachLoader
     */
    public function testAttachLoaderWithIdentificationKey()
    {
        $loader = $this->getDataLoaderStub();
        $expected = array('tux' => $loader);

        $da = new DataAggregator();
        $da->attachLoader($loader, 'tux');

        $this->assertEquals($expected, $da->loaders);
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::detachLoader
     */
    public function testDetachLoader()
    {
        $loader = $this->getDataLoaderStub();

        $da = new DataAggregator();
        $da->attachLoader($loader, 'TuxLoader');
        $da->detachLoader('TuxLoader');

        $this->assertEmpty($da->loaders);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @covers \Liip\DataAggregator\DataAggregator::detachLoader
     */
    public function testDetachLoaderExceptionInvalidArgumentException()
    {
        $da = new DataAggregator();
        $da->detachLoader('NotExistingLoaderKey');
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::run
     * @covers \Liip\DataAggregator\DataAggregator::__construct
     */
    public function testRun()
    {
        $loader = $this->getDataLoaderMock(array('load', 'stopPropagation'));
        $loader
            ->expects($this->atLeastOnce())
            ->method('load')
            ->will($this->returnValue(array()));

        $persistor = $this->getDataPersistorMock(array('persist'));
        $persistor
            ->expects($this->once())
            ->method('persist')
            ->with($this->isType('array'));

        $da = new DataAggregator();
        $da->attachLoader($loader);
        $da->attachPersistor($persistor);
        $da->run();
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::run
     */
    public function testRunExpectingLoaderToThrowDataLoaderException()
    {
        $loader = $this->getDataLoaderMock(array('load', 'stopPropagation'));
        $loader
            ->expects($this->atLeastOnce())
            ->method('load')
            ->will($this->throwException(new LoaderException()));

        $persistor = $this->getDataPersistorMock();

        $da = new DataAggregator();
        $da->attachLoader($loader);
        $da->attachPersistor($persistor);
        $da->run();
    }

    /**
     * @dataProvider runMultipleLoadersDataprovider
     * @covers \Liip\DataAggregator\DataAggregator::run
     */
    public function testRunMultipleLoaders($expected, $loaderResult, $propagationStopped)
    {
        $loader = $this->getDataLoaderMock(array('load', 'stopPropagation'));
        $loader
            ->expects($this->atLeastOnce())
            ->method('load')
            ->will(
                $this->returnValue($loaderResult)
            );

        $loader
            ->expects($this->atLeastOnce())
            ->method('stopPropagation')
            ->will(
                $this->returnValue($propagationStopped)
            );

        $da = new DataAggregator();
        $da->attachLoader($loader, 'tux');
        $da->attachLoader($loader, 'gnu');
        $da->attachPersistor($this->getDataPersistorMock());

        $da->run();

        $this->assertAttributeEquals($expected, 'loaderResults', $da);
    }

    /**
     * Provides several scenes to be verified by the corresponding test.
     * @return array
     */
    public static function runMultipleLoadersDataprovider()
    {
        $object = new \stdClass();

        return array(
            'run both registered loaders' => array(
                array($object, $object),
                array($object),
                false
            ),
            '1st loader stops propagation' => array(
                array($object),
                array($object),
                true
            ),
        );
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::attachPersistor
     */
    public function testAttachPersistor()
    {
        $persistor = $this->getMock('\\Liip\\DataAggregator\\Persistors\\PersistorInterface');
        $expected = array($persistor);

        $da = new DataAggregator();

        $da->attachPersistor($persistor);

        $this->assertAttributeEquals($expected, 'persistors', $da);
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::attachPersistor
     */
    public function testAttachPersistorWithIdentificationKey()
    {
        $persistor = $this->getDataPersistorMock();
        $expected = array('Suse' => $persistor);

        $da = new DataAggregator();

        $da->attachPersistor($persistor, 'Suse');

        $this->assertAttributeEquals($expected, 'persistors', $da);
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::detachPersistor
     */
    public function testDetachPersistor()
    {
        $persistor = $this->getDataPersistorMock();
        $expected = array();

        $da = new DataAggregator();

        $da->attachPersistor($persistor, 'suse');
        $da->detachPersistor('suse');

        $this->assertAttributeEquals($expected, 'persistors', $da);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @covers \Liip\DataAggregator\DataAggregator::detachPersistor
     */
    public function testDetachPersistorExpectionInvalidArgumentException()
    {
        $da = new DataAggregator();
        $da->detachPersistor('NotExistingLoaderKey');
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::persist
     */
    public function testPersist()
    {
        $collection = array();

        $persistor = $this->getDataPersistorMock(array('persist'));

        $persistor
            ->expects($this->once())
            ->method('persist')
            ->with($this->isType('array'));

        $da = new DataAggregator();

        $da->attachPersistor($persistor);
        $da->persist($collection);
    }

    /**
     * @expectedException \Liip\DataAggregator\DataAggregatorException
     * @covers \Liip\DataAggregator\DataAggregator::persist
     */
    public function testPersistExpectingDataAggregatorException()
    {
        $da = new DataAggregator();
        $da->persist(array());
    }
}
