<?php

namespace Liip\DataAggregator;


interface DataAggregatorInterface
{
    /**
     * Executes the processing of every attached loader
     */
    public function run();

    /**
     * Forwards the gathered data to every registered output handler.
     *
     * @param array $data
     */
    public function persist(array $data);
}
