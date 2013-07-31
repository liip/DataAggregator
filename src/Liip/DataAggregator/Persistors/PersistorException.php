<?php

namespace Liip\DataAggregator\Persistors;

/**
 * Dummy implementation of an exception, just to have a dedicated name for exceptions caused by a persistor.
 *
 * @package Liip\DataAggregator\Persistors
 */
class PersistorException extends \Exception
{
    const NO_ENTITY_DATA_TO_PERSIST  = 1;
}
