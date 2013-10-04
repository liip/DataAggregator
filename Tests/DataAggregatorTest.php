<?php
namespace Liip\DataAggregator\Tests;

use Liip\DataAggregator\DataAggregator;
use Liip\DataAggregator\DataAggregatorException;
use Liip\DataAggregator\Loaders\LoaderException;
use Liip\DataAggregator\Persistors\PersistorException;

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

        $da = $this->getProxyBuilder('\Liip\DataAggregator\DataAggregator')
            ->setMethods(array('persist'))
            ->getProxy();

        $da->attachPersistor($persistor);
        $da->persist($collection);
    }

    /**
     * @expectedException \Liip\DataAggregator\DataAggregatorException
     * @covers \Liip\DataAggregator\DataAggregator::persist
     */
    public function testPersistExpectingDataAggregatorException()
    {
        $da = $this->getProxyBuilder('\Liip\DataAggregator\DataAggregator')
            ->setMethods(array('persist'))
            ->getProxy();
        $da->persist(array());
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::persist
     */
    public function testLogWhilePersist()
    {
        $logger = $this->getMockBuilder('\\Psr\\Log\\AbstractLogger')
            ->setMethods(array('error'))
            ->getMockForAbstractClass();
        $logger
            ->expects($this->once())
            ->method('error')
            ->with($this->isType('string'));

        $persistor = $this->getDataPersistorMock(array('persist'));

        $persistor
            ->expects($this->once())
            ->method('persist')
            ->will($this->throwException(new PersistorException('FAILED')));

        $da = $this->getProxyBuilder('\Liip\DataAggregator\DataAggregator')
            ->setMethods(array('persist'))
            ->getProxy();

        $da->setLogger($logger);

        $da->attachPersistor($persistor);
        $da->persist(array());
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::getLogger
     */
    public function testGetLogger()
    {
        /** @var \Liip\DataAggregator\DataAggregatorBatch $da */
        $da = new DataAggregator();

        $this->assertInstanceOf('\\Psr\\Log\\NullLogger', $da->getLogger());
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregator::getLogger
     * @covers \Liip\DataAggregator\DataAggregator::setLogger
     */
    public function testGetLoggerFromCache()
    {
        $logger = $this->getMockForAbstractClass('\\Psr\\Log\\AbstractLogger');

        /** @var \Liip\DataAggregator\DataAggregatorBatch $da */
        $da = new DataAggregator();

        $da->setLogger($logger);

        $this->assertInstanceOf('\\Psr\\Log\\AbstractLogger', $da->getLogger());
    }
}
