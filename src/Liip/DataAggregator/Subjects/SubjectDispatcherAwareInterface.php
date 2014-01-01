<?php
namespace Liip\DataAggregator\Subjects;

/**
 * Interface SubjectDispatcherAwareInterface
 * @package LiipDataAggregatorSubjects
 */
interface SubjectDispatcherAwareInterface {

    /**
     * Defines the subject dispatcher to be used in the current context.
     *
     * @param SubjectDispatcherInterface $dispatcher
     */
    public function setSubjectDispatcher(SubjectDispatcherInterface $dispatcher);
}
