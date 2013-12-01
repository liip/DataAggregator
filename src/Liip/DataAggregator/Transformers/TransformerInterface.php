<?php
namespace Liip\DataAggregator\Transformers;

/**
 * Class TransformerInterface
 *
 * @package Liip\DataAggregator\Transformers
 */
interface TransformerInterface
{
    /**
     * Maps a provided data blob to a dedicated data structure know to the persistor.
     *
     * @param mixed $data
     *
     * @return mixed
     */
    public function transform($data);
}
