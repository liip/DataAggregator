<?php

namespace Liip\DataAggregator\Subjects;


interface SubjectDispatcherAwareInterface {

    /**
     * Defines the subject dispatcher to be used in the current context.
     *
     * @param SubjectDispatcherInterface $dispatcher
     */
    public function setSubjectDispatcher(SubjectDispatcherInterface $dispatcher);
}
