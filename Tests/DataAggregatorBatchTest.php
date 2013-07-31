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

        $da = new DataAggregatorBatch();
        $da->attachLoader($loader);

        $loaders = $this->readAttribute($da, 'loaders');
        $this->assertContainsOnly($loader, $loaders);
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

        $this->assertEquals($expected, $this->readAttribute($da, 'loaders'));
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

        $this->assertNotContains('TuxLoader', $this->readAttribute($da, 'loaders'));
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
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

        $da = new DataAggregatorBatch();

        $da->attachPersistor($persistor, 'suse');
        $da->detachPersistor('suse');

        $persistors = $this->readAttribute($da, 'persistors');
        $this->assertNotContains('suse', $persistors);
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @covers \Liip\DataAggregator\DataAggregatorBatch::detachPersistor
     */
    public function testDetachPersistorExpectionInvalidArgumentException()
    {
        $da = new DataAggregatorBatch();
        $da->detachPersistor('NotExistingLoaderKey');
    }

    /**
     * @covers \Liip\DataAggregator\DataAggregatorBatch::run
     * @covers \Liip\DataAggregator\DataAggregatorBatch::executeLoader
     */
    public function testExecuteLoader()
    {
        $persistor = $this->getDataPersistorMock(array('persist'));
        $persistor
            ->expects($this->exactly(2))
            ->method('persist')
            ->with($this->isType('array'));

        $loader = $this->getDataLoaderBatchStub(array('load'));
        $loader
            ->expects($this->exactly(2))
            ->method('load')
            ->with(
                $this->isType('integer'),
                $this->isType('integer')
            )
            ->will(
                $this->onConsecutiveCalls(
                    array('tux', 'gnu'),
                    array('tux')
                )
            );

        /** @var \Liip\DataAggregator\DataAggregatorBatch $da */
        $da = new DataAggregatorBatch();

        $da->attachLoader($loader);
        $da->attachPersistor($persistor);

        $da->setLimit(2);
        $da->run();
    }

    /**
     * @expectedException \Assert\InvalidArgumentException
     * @covers \Liip\DataAggregator\DataAggregatorBatch::run
     * @covers \Liip\DataAggregator\DataAggregatorBatch::getLogger
     */
    public function testRunExpectingException()
    {
        /** @var \Liip\DataAggregator\DataAggregatorBatch $da */
        $da = new DataAggregatorBatch();
        $da->run();
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

    /**
     * @covers \Liip\DataAggregator\DataAggregatorBatch::persist
     */
    public function testPersist()
    {
        $collection = array();

        $persistor = $this->getDataPersistorMock(array('persist'));

        $persistor
            ->expects($this->once())
            ->method('persist')
            ->with($this->isType('array'));

        $da = new DataAggregatorBatch();

        $da->attachPersistor($persistor);
        $da->persist($collection);
    }

    /**
     * @expectedException \Liip\DataAggregator\DataAggregatorException
     * @covers \Liip\DataAggregator\DataAggregatorBatch::persist
     */
    public function testPersistExpectingDataAggregatorException()
    {
        $da = new DataAggregatorBatch();
        $da->persist(array());
    }
}
