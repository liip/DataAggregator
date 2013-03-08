<?php

namespace Liip\DataAggregator;

class DataAggregatorException extends \Exception
{
    const LOADER_NOT_FOUND = 1;
    const PERSISTOR_NOT_FOUND = 2;
    const NO_PERSISTOR_ATTACHED = 3;
}
