<?php

namespace Liip\DataAggregator\Tests;

use lapistano\ProxyObject\ProxyBuilder;

abstract class DataAggregatorTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides an instance of the ProxyBuilder
     *
     * @param string $className
     *
     * @return \lapistano\ProxyObject\ProxyBuilder
     */
    protected function getProxyBuilder($className)
    {
        return new ProxyBuilder($className);
    }

    /**
     * Provides a test double for the assertion library
     *
     * @param array $methods
     *
     * @return \PHPUnit_Framework_MockObject_MockBuilder
     */
    protected function getAssertionMock(array $methods = array())
    {
        return $this->getMockBuilder('\Assert\Assertion')
            ->setMethods($methods)
            ->getMock();
    }

    /**
     * Provides a stub of the LoaderInterface.
     *
     * @return \Liip\DataAggregator\Loaders\LoaderInterface
     */
    protected function getDataLoaderStub()
    {
        return $this->getMockBuilder(
            '\\Liip\\DataAggregator\\Loaders\\LoaderInterface'
        )
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    /**
     * Provides a mock of the LoaderInterface.
     *
     * @return \Liip\DataAggregator\Loaders\LoaderInterface
     */
    protected function getDataLoaderMock(array $methods = array())
    {
        return $this->getMockBuilder(
            '\\Liip\\DataAggregator\\Loaders\\LoaderInterface'
        )
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }

    /**
     * Provides a stub of the PersistorInterface.
     *
     * @return \Liip\DataAggregator\Persistors\PersistorInterface
     */
    protected function getDataPersistorStub()
    {
        return $this->getMockBuilder(
            '\\Liip\\DataAggregator\\Persistors\\PersistorInterface'
        )
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }

    /**
     * Provides a mock of the PersistorInterface.
     *
     * @return \Liip\DataAggregator\Persistors\PersistorInterface
     */
    protected function getDataPersistorMock(array $methods = array())
    {
        return $this->getMockBuilder(
            '\\Liip\\DataAggregator\\Persistors\\PersistorInterface'
        )
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }

    /**
     * Provides a configuration array.
     *
     * @return array
     */
    protected function getLoaderBossConfigurationFixture()
    {
        return array(
            'BOSS_ID'            => 'BossId',
            'STUFE'              => 'Stufe',
            'BW_ID'              => 'Bw',
            'BB_ID'              => 'Bb',
            'VS_ID'              => 'Vs',
            'TH_ID'              => 'Th',
            'FA_ID'              => 'Fa',
            'BEZ_D'              => 'TitleDe',
            'BEZ_F'              => 'TitleFr',
            'BEZ_I'              => 'TitleIt',
            'BEZ_E'              => 'TitleEn',
            'AKTUELL_STATUS'     => 'Status',
            'AKTUELL_GUELTIG_AB' => 'ActiveFromDate',
            'VERANTWORTLICH'     => 'Responsible',
            'SPARTE'             => 'Category',
            'FOLGE_STATUS'       => 'PostStatus',
            'FOLGE_GUELTIG_AB'   => 'PostActiveFromDate',
            'RPA_FLAG'           => 'Rpa',
        );
    }

    /**
     * Provides a configuration array.
     *
     * @return array
     */
    protected function getPersistorBossConfigurationFixture()
    {
        return array(
            'BossId'             => 'BossId',
            'Stufe'              => 'Stufe',
            'Bw'                 => 'Bw',
            'Bb'                 => 'Bb',
            'Vs'                 => 'Vs',
            'Th'                 => 'Th',
            'Fa'                 => 'Fa',
            'TitleDe'            => 'TitleDe',
            'TitleFr'            => 'TitleFr',
            'TitleIt'            => 'TitleIt',
            'TitleEn'            => 'TitleEn',
            'Status'             => 'Status',
            'ActiveFromDate'     => 'ActiveFromDate',
            'Responsible'        => 'Responsible',
            'Category'           => 'Category',
            'PostStatus'         => 'PostStatus',
            'PostActiveFromDate' => 'PostActiveFromDate',
            'Rpa'                => 'Rpa',
        );
    }
}
