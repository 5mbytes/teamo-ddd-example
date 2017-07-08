<?php
declare(strict_types=1);

namespace Tests\Unit\Project\Domain\Model\Project;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Project\Attachment\Attachment;
use Teamo\Project\Domain\Model\Project\Attachment\AttachmentId;
use Teamo\Project\Domain\Model\Project\Discussion\Discussion;
use Teamo\Project\Domain\Model\Project\Discussion\DiscussionId;
use Teamo\Project\Domain\Model\Project\Event\Event;
use Teamo\Project\Domain\Model\Project\Event\EventId;
use Teamo\Project\Domain\Model\Project\Project;
use Teamo\Project\Domain\Model\Project\ProjectId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoList;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Collaborator\Creator;
use Teamo\Project\Domain\Model\Owner\OwnerId;
use Teamo\Project\Domain\Model\Project\TodoList\TodoListId;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    /**
     * @var Project
     */
    private $project;

    public function setUp()
    {
        $this->project = new Project(new OwnerId('id-1'), new ProjectId('id-1'), 'My Project');
    }

    public function testConstructedProjectIsValid()
    {
        $ownerId = new OwnerId('owner');
        $projectId = new ProjectId('project');

        $project = new Project($ownerId, $projectId, 'My Project');

        $this->assertSame($ownerId, $project->ownerId());
        $this->assertSame($projectId, $project->projectId());
        $this->assertEquals('My Project', $project->name());
    }

    public function testProjectCanStartDiscussion()
    {
        $author = new Author('id-1', 'John Doe');
        $attachments = new Collection(new Attachment(new AttachmentId('1'), 'attachment.txt'));

        $discussion = $this->project->startDiscussion(new DiscussionId('1'), $author, 'New Discussion', 'Discussion content', $attachments);

        $this->assertInstanceOf(Discussion::class, $discussion);
    }

    public function testProjectCanCreateTodoList()
    {
        $creator = new Creator('id-1', 'John Doe');
        $todoList = $this->project->createTodoList(new TodoListId('1'), $creator, 'New Todo List');

        $this->assertInstanceOf(TodoList::class, $todoList);
    }

    public function testProjectCanScheduleEvent()
    {
        $creator = new Creator('id-1', 'John Doe');
        $attachments = new Collection(new Attachment(new AttachmentId('1'), 'attachment.txt'));

        $event = $this->project->scheduleEvent(new EventId('1'), $creator, 'My Event', 'Event details', '2020-01-01 00:00:00', $attachments);

        $this->assertInstanceOf(Event::class, $event);
    }

    public function testProjectCanBeRenamed()
    {
        $this->assertEquals('My Project', $this->project->name());

        $this->project->rename('Project');
        $this->assertEquals('Project', $this->project->name());
    }

    public function testProjectCanBeArchivedAndRestored()
    {
        $this->assertFalse($this->project->isArchived());

        $this->project->archive();
        $this->assertTrue($this->project->isArchived());

        $this->project->restore();
        $this->assertFalse($this->project->isArchived());
    }
}