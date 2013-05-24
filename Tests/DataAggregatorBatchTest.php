<?php

namespace Liip\DataAggregator\Tests;

use Liip\DataAggregator\DataAggregatorBatch;


class DataAggregatorBatchTest extends DataAggregatorTestCase
{
    /**
     * @covers \Liip\DataAggregator\DataAggregatorBatch::attachLoader
     */
    public function testAttachLoader()
    {
        $loader = $this->getDataLoaderBatchStub();
        $expected = array('TuxLoader' => $loader);

        $da = new DataAggregatorBatch();
        $da->attachLoader($loader, 'TuxLoader');

        $this->assertEquals($expected, $da->loaders);
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregatorBatch::attachLoader
     */
    public function testAttachLoaderWithIdentificationKey()
    {
        $loader = $this->getDataLoaderBatchStub();
        $expected = array('tux' => $loader);

        $da = new DataAggregatorBatch();
        $da->attachLoader($loader, 'tux');

        $this->assertEquals($expected, $da->loaders);
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregatorBatch::detachLoader
     */
    public function testDetachLoader()
    {
        $loader = $this->getDataLoaderBatchStub();

        $da = new DataAggregatorBatch();
        $da->attachLoader($loader, 'TuxLoader');
        $da->detachLoader('TuxLoader');

        $this->assertEmpty($da->loaders);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @covers \Liip\DataAggregator\DataAggregatorBatch::detachLoader
     */
    public function testDetachLoaderExceptionInvalidArgumentException()
    {
        $da = new DataAggregatorBatch();
        $da->detachLoader('NotExistingLoaderKey');
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregatorBatch::attachPersistor
     */
    public function testAttachPersistor()
    {
        $persistor = $this->getMock('\\Liip\\DataAggregator\\Persistors\\PersistorInterface');
        $expected = array($persistor);

        $da = new DataAggregatorBatch();

        $da->attachPersistor($persistor);

        $this->assertAttributeEquals($expected, 'persistors', $da);
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregatorBatch::attachPersistor
     */
    public function testAttachPersistorWithIdentificationKey()
    {
        $persistor = $this->getDataPersistorMock();
        $expected = array('Suse' => $persistor);

        $da = new DataAggregatorBatch();

        $da->attachPersistor($persistor, 'Suse');

        $this->assertAttributeEquals($expected, 'persistors', $da);
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregatorBatch::detachPersistor
     */
    public function testDetachPersistor()
    {
        $persistor = $this->getDataPersistorMock();
        $expected = array();

        $da = new DataAggregatorBatch();

        $da->attachPersistor($persistor, 'suse');
        $da->detachPersistor('suse');

        $this->assertAttributeEquals($expected, 'persistors', $da);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @covers \Liip\DataAggregator\DataAggregatorBatch::detachPersistor
     */
    public function testDetachPersistorExpectionInvalidArgumentException()
    {
        $da = new DataAggregatorBatch();
        $da->detachPersistor('NotExistingLoaderKey');
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregatorBatch::executeLoader
     */
    public function testExecuteLoader()
    {
        $this->markTestIncomplete();

        $loader = $this->getDataLoaderBatchStub(array('load'));
        $loader
            ->expects($this->once())
            ->method('load')
            ->will($this->returnValue(array()));

        $da = $this->getProxyBuilder('\\Liip\\DataAggregator\\DataAggregatorBatch')
            ->setMethods(array('executeLoader'))
            ->getProxy();

        $da->setLimit(1);
        $da->executeLoader($loader);
    }

    /**
     * @dataProvider setLimitDataprovider
     * @covers \Liip\DataAggregator\DataAggregatorBatch::getLimit
     * @covers \Liip\DataAggregator\DataAggregatorBatch::setLimit
     */
    public function testLimit($expected, $limit)
    {
        $da = new DataAggregatorBatch();
        $da->setLimit($limit);

        $this->assertEquals($expected, $da->getLimit());
    }
    public static function setLimitDataprovider()
    {
        return array(
            'positive limit' => array(15, 15),
            'no limit' => array(0, 0),
        );
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @dataProvider limitDataprovider
     * @covers \Liip\DataAggregator\DataAggregatorBatch::getLimit
     */
    public function testLimitExpectingException($limit)
    {

        $da = new DataAggregatorBatch();
        $da->setLimit($limit);
    }
    public static function limitDataprovider()
    {
        return array(
            'of type »string«' => array('invalid Limit'),
            'of type »boolean«' => array(false),
            'of type »array«' => array(array()),
            'of type »object«' => array(new \stdClass()),
            'of type »integer« but negative' => array(-15),
        );
    }

}
