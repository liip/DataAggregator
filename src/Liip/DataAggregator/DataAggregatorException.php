<?php

namespace Liip\DataAggregator;

/**
 * Dummy implementation of an exception, just to have a dedicated name for exceptions caused by an aggregator.
 *
 * @package Liip\DataAggregator
 */
class DataAggregatorException extends \Exception
{
    const LOADER_NOT_FOUND = 1;
    const PERSISTOR_NOT_FOUND = 2;
    const NO_PERSISTOR_ATTACHED = 3;
    const NO_LOADER_ATTACHED = 4;
}
