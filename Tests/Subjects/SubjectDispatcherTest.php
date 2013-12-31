<?php
namespace Liip\DataAggregator\Tests\Subjects;

use Liip\DataAggregator\Subjects\SubjectDispatcher;
use Liip\DataAggregator\Tests\DataAggregatorTestCase;

/**
 * Class SubjectDispatcherTest
 * @package LiipDataAggregatorTestsSubjects
 */
class SubjectDispatcherTest  extends DataAggregatorTestCase
{
    /**
     * @covers Liip\DataAggregator\Subjects\SubjectDispatcher::emit
     */
    public function testEmit()
    {
        $subject = $this->getMock('\SplSubject');
        $subject
            ->expects($this->once())
            ->method('notify');

        $dispatcher = new SubjectDispatcher();

        $dispatcher->attachSubject('testEvent', $subject);

        $this->assertNull($dispatcher->emit('testEvent'));
    }

    /**
     * @covers Liip\DataAggregator\Subjects\SubjectDispatcher::attachSubject
     * @covers Liip\DataAggregator\Subjects\SubjectDispatcher::getUniqueKey
     */
    public function testAttachSubject()
    {
        $subject = $this->getMock('\SplSubject');
        $event = 'testEvent';

        $dispatcher = new SubjectDispatcher();
        $key = $dispatcher->attachSubject($event, $subject);

        $subjects = $this->readAttribute($dispatcher, 'subjects');

        $this->assertInstanceOf('\SplSubject', $subjects[$event][$key]);
    }

    /**
     * @covers Liip\DataAggregator\Subjects\SubjectDispatcher::detachSubject
     */
    public function testDetachSubject()
    {
        $subject = $this->getMock('\SplSubject');
        $event = 'testEvent';

        $dispatcher = new SubjectDispatcher();
        $key1 = $dispatcher->attachSubject($event, $subject);
        $key2 = $dispatcher->attachSubject($event, $subject);

        $subjects = $this->readAttribute($dispatcher, 'subjects');

        $this->assertInstanceOf('\SplSubject', $subjects[$event][$key1]);
        $this->assertInstanceOf('\SplSubject', $subjects[$event][$key2]);

        $dispatcher->detachSubject($event, $key1);

        $subjects = $this->readAttribute($dispatcher, 'subjects');

        $this->assertCount(1, $subjects[$event]);

    }

    /**
     * @covers Liip\DataAggregator\Subjects\SubjectDispatcher::detachSubject
     */
    public function testDetachAttemptOnUnknownEvent()
    {
        $dispatcher = new SubjectDispatcher();

        $this->setExpectedException('\Assert\InvalidArgumentException', 'Provided event does not exist.');

        $dispatcher->detachSubject('testEvent', 'invalid key');
    }

    /**
     * @covers Liip\DataAggregator\Subjects\SubjectDispatcher::detachSubject
     */
    public function testDetachUnregisteredSubject()
    {
        $event = 'testEvent';
        $subject = $this->getMock('\SplSubject');

        $dispatcher = new SubjectDispatcher();
        $dispatcher->attachSubject($event, $subject);

        $this->setExpectedException('\Assert\InvalidArgumentException', 'Subject to be detached does not exist.');

        $dispatcher->detachSubject($event, 'invalid key');
    }
}
