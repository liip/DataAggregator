<?php
namespace Liip\DataAggregator\Loaders;

class LoaderExample implements LoaderInterface
{
    /**
     * Starts the data loading process.
     *
     * @return array
     */
    public function load()
    {
        $result = array();
        return $this->process($result);
    }

    /**
     * Backport to the DataAggregator.
     *
     * This backport shall enable the DataAggregator to decide if to stop
     * over other registered loaders.
     *
     * @return boolean
     */
    public function stopPropagation()
    {
        return false;
    }

    /**
     * Converts data to an Object
     *
     * @param array $data
     *
     * @return array
     */
    protected function process(array $data)
    {
        $processedData = array();

        return $processedData;
    }
}
