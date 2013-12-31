<?php
namespace Liip\DataAggregator\Subjects;
use Assert\Assertion;


/**
 * Class SubjectDispatcher
 * @package LiipDataAggregatorSubjects
 */
class SubjectDispatcher implements SubjectDispatcherInterface
{
    /** @var \SplSubject[] set of subjects to be emitted on a specific event. */
    protected $subjects = array();

    /**
     * Registers a subject to be called an a specific event.
     *
     * @param string $event
     * @param \SplSubject $subject
     *
     * @return string
     */
    public function attachSubject($event, \SplSubject $subject)
    {
        $key = $this->getUniqueKey($event);

        if (!array_key_exists($event, $this->subjects)) {
            $this->subjects[$event] = array();
        }

        $this->subjects[$event][$key] = $subject;

        return $key;
    }

    /**
     * Unregisters a subject from a specific event.
     *
     * @param string $event
     * @param string $key
     */
    public function detachSubject($event, $key)
    {
        Assertion::notEmptyKey($this->subjects, $event, 'Provided event does not exist.');
        Assertion::notEmptyKey($this->subjects[$event], $key, 'Subject to be detached does not exist.');

        unset($this->subjects[$event][$key]);
    }


    /**
     * Triggers every subject attached to an event.
     *
     * @param string $event
     */
    public function emit($event)
    {
        if (!empty($this->subjects[$event])) {

            /** @var \SplSubject $subject */
            foreach($this->subjects[$event] as $subject) {
                $subject->notify();
            }
        }
    }

    /**
     * Generates a key as unique as possible.
     *
     * @return string
     */
    protected function getUniqueKey()
    {
        return sha1(PHP_OS .PHP_SAPI . PHP_VERSION . microtime(true));
    }
}
