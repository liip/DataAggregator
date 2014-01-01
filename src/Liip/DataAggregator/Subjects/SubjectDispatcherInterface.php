<?php
namespace Liip\DataAggregator\Subjects;

/**
 * Interface SubjectDispatcherInterface
 * @package LiipDataAggregatorSubjects
 */
interface SubjectDispatcherInterface {

    /**
     * Registers a subject to be called an a specific event.
     *
     * @param string $event
     * @param \SplSubject $subject
     */
    public function attachSubject($event, \SplSubject $subject);

    /**
     * Unregisters a subject from a specific event.
     *
     * @param string $event
     * @param string $key
     */
    public function detachSubject($event, $key);

    /**
     * Triggers every subject attached to an event.
     *
     * @param $event
     */
    public function emit($event);
}
