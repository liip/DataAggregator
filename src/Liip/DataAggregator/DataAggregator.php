<?php
namespace Liip\DataAggregator;

use Liip\DataAggregator\Components\Loaders\LoaderDefaultInterface;
use Liip\DataAggregator\Components\Persistors\PersistorDefaultInterface;
use Liip\DataAggregator\Loaders\LoaderException;
use Liip\DataAggregator\Loaders\LoaderInterface;
use Liip\DataAggregator\Persistors\PersistorException;
use Liip\DataAggregator\Persistors\PersistorInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 *  The DataAggregator cumulates information provides by attached loaders and routes it to registered output handler.
 */
class DataAggregator implements DataAggregatorInterface, PersistorDefaultInterface, LoaderDefaultInterface, LoggerAwareInterface
{
    /**
     * Registry of attached loaders.
     * @var array
     */
    public $loaders = array();

    /**
     * Collection of value objects returned from a loader.
     * @var array
     */
    protected $loaderResults = array();

    /**
     * Registry of attached persistors.
     * @var array
     */
    protected $persistors = array();

    /**
     * Defines the logger to be used.
     * @var LoggerInterface
     */
    protected $logger;


    /**
     * Adds given loader to registry.
     *
     * @param \Liip\DataAggregator\Loaders\LoaderInterface $loader
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
     * @throws \InvalidArgumentException in case there is no loader registered with the given key.
     */
    public function detachLoader($key)
    {
        if (!empty($this->loaders[$key])) {

            unset($this->loaders[$key]);

        } else {
            throw new \InvalidArgumentException(
                'No registered loader found.',
                DataAggregatorException::LOADER_NOT_FOUND
            );
        }
    }

    /**
     * Adds the given persistor to the collection of output handlers
     *
     * @param \Liip\DataAggregator\Persistors\PersistorInterface $persistor
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
     * @throws \InvalidArgumentException in case the given key does not correspond to a persistor.
     */
    public function detachPersistor($key)
    {
        if (!empty($this->persistors[$key])) {

            unset($this->persistors[$key]);

        } else {

            throw new \InvalidArgumentException(
                'No registered persistor found.',
                DataAggregatorException::PERSISTOR_NOT_FOUND
            );
        }
    }

    /**
     * Executes the processing of every attached loader
     */
    public function run()
    {
        foreach ($this->loaders as $identifier => $loader) {
            try {
                $this->loaderResults = array_merge($this->loaderResults, $loader->load());

                if ($loader->stopPropagation()) {
                    break;
                }
            } catch (LoaderException $e) {
                $this->getLogger()->error($e->getMessage());
            }
        }

        try {

            $this->persist($this->loaderResults);

        } catch (PersistorException $e) {
            $this->getLogger()->error($e->getMessage());
        }
    }

    /**
     * Forwards the gathered data to every registered output handler.
     *
     * @param array $data
     *
     * @throws DataAggregatorException in case no output handler is attached.
     */
    public function persist(array $data)
    {
        if (empty($this->persistors)) {

            throw new DataAggregatorException(
                'No persistor attached.',
                DataAggregatorException::NO_PERSISTOR_ATTACHED
            );
        }

        foreach ($this->persistors as $persistor) {
            try {
                $persistor->persist($data);

            } catch (DataAggregatorException $e) {
                $this->getLogger()->error($e->getMessage());
            }
        }
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
