<?php
namespace Liip\DataAggregator\Loaders;

interface LoaderInterface
{
    /**
     * Starts the data loading process.
     */
    public function load();

    /**
     * Backport to the DataAggregator.
     *
     * This backport shall enable the DataAggregator to decide if to stop
     * over other registered loaders.
     *
     * @return boolean
     */
    public function stopPropagation();
}
