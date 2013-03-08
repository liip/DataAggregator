<?php

namespace Liip\DataAggregator\Persistors;

interface PersistorInterface
{
    /**
     * Persists provided data.
     *
     * @param array $data
     */
    public function persist(array $data);
}
