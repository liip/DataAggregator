<?php
namespace Liip\DataAggregator\Persistors;

class PersistorExample implements PersistorInterface
{
    /**
     * Set of configuration values.
     * @var array
     */
    protected $configuration = array();


    /**
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Processes the given data.
     *
     * @param array $data
     */
    public function persist(array $data)
    {

    }
}
