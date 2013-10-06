<?php
namespace Liip\DataAggregator;

use Assert\Assertion;
use Liip\DataAggregator\Components\Loaders\LoaderBatchInterface;
use Liip\DataAggregator\Components\Persistors\PersistorDefaultInterface;
use Liip\DataAggregator\Loaders\LoaderException;
use Liip\DataAggregator\Loaders\LoaderBatchInterface as LoaderInterface;
use Liip\DataAggregator\Persistors\PersistorException;
use Liip\DataAggregator\Persistors\PersistorInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 *  The DataAggregator cumulates information provides by attached loaders and routes it to registered output handler.
 */
class DataAggregatorBatch implements DataAggregatorBatchInterface, LoaderBatchInterface, PersistorDefaultInterface, LoggerAwareInterface
{
    /**
     * Registry of attached loaders.
     * @var LoaderInterface[]
     */
    protected $loaders = array();

    /**
     * Registry of attached persistors.
     * @var PersistorInterface[]
     */
    protected $persistors = array();

    /**
     * Defines the amount of items to be fetched by a loader.
     *
     * Zero defines an unlimited amount of items
     *
     * @var integer
     */
    protected $limit = 0;

    /**
     * Defines the logger to be used.
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Executes the processing of every attached loader.
     *
     * Run each loader with limit and offset as long as it
     * returns not less than the limit of items persist
     * after each batch.
     *
     * @throws \Assert\InvalidArgumentException in case no loader is attached.
     *
     */
    public function run()
    {
        Assertion::notEmpty(
            $this->loaders,
            'No loader attached.',
            DataAggregatorException::NO_LOADER_ATTACHED
        );

        foreach ($this->loaders as $identifier => $loader) {
            $this->executeLoader($loader);
        }
    }

    /**
     * Runs the addressed loader to retrieve its' data.
     *
     * Any exception or error will be logged to the systems error log.
     *
     * @param LoaderInterface $loader
     */
    protected function executeLoader(LoaderInterface $loader)
    {
        $offset = 0;

        try {

            while ($result = $loader->load($this->limit, $offset)) {

                $this->persist($result);
                $offset += $this->limit;
            }

        } catch (LoaderException $e) {
            $this->getLogger()->error($e->getMessage());
        }
    }

    /**
     * Defines the amount of entities to be fetched from the information provider.
     *
     * @param integer $limit Positive integer defining the amount of records to be return in max. Zero (0) defines an unlimited amount.
     *
     * @throws \Assert\InvalidArgumentException in case the provided argument does not meet expectations.
     */
    public function setLimit($limit)
    {
        $message = 'Given limit must be a positive number.';
        Assertion::numeric($limit, $message);
        Assertion::min($limit, 0, $message);

        $this->limit = $limit;
    }

    /**
     * Provides the currently set query limit.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Forwards the gathered data to every registered output handler.
     *
     * @param array $data
     *
     * @throws InvalidArgumentException in case no persistor has been attached.
     */
    protected function persist(array $data)
    {
        Assertion::notEmpty(
            $this->persistors,
            'No persistor attached.',
            DataAggregatorException::NO_PERSISTOR_ATTACHED
        );

        foreach ($this->persistors as $persistor) {
            try {
                $persistor->persist($data);

            } catch (PersistorException $e) {
                $this->getLogger()->error($e->getMessage());
            }
        }
    }

    /**
     * Adds given loader to registry.
     *
     * @param LoaderInterface $loader
     * @param string $key
     */
    public function attachLoader(LoaderInterface $loader, $key = '')
    {
        if (empty($key)) {
            $this->loaders[] = $loader;
        } else {
            $this->loaders[$key] = $loader;
        }
    }

    /**
     * Removes a loader identified by the given key from the registry.
     *
     * @param string $key
     *
     * @throws \Assert\InvalidArgumentException in case there is no loader registered with the given key.
     */
    public function detachLoader($key)
    {
        Assertion::keyExists(
            $this->loaders,
            $key,
            'No loader represented by '. $key .' found.',
            DataAggregatorException::LOADER_NOT_FOUND
        );

        unset($this->loaders[$key]);
    }

    /**
     * Adds the given persistor to the collection of output handlers
     *
     * @param PersistorInterface $persistor
     * @param string $key
     */
    public function attachPersistor(PersistorInterface $persistor, $key = '')
    {
        if (empty($key)) {
            $this->persistors[] = $persistor;
        } else {
            $this->persistors[$key] = $persistor;
        }
    }

    /**
     * Removes a persistor identified by the given key from the registry.
     *
     * @param string $key
     *
     * @throws \Assert\InvalidArgumentException in case the given key does not correspond to a persistor.
     */
    public function detachPersistor($key)
    {
        Assertion::keyExists(
            $this->persistors,
            $key,
            'No registered persistor found.',
            DataAggregatorException::PERSISTOR_NOT_FOUND
        );

        unset($this->persistors[$key]);
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Provides the instance of the currently set logger.
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (empty($this->logger)) {
            $this->logger = new NullLogger();
        }

        return $this->logger;
    }
}
