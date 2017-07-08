<?php
declare(strict_types=1);

namespace Teamo\Project\Domain\Model\Project\TodoList;

use Illuminate\Support\Collection;
use Teamo\Project\Domain\Model\Collaborator\Author;
use Teamo\Project\Domain\Model\Project\Comment\Comment;
use Teamo\Project\Domain\Model\Project\Comment\CommentId;

class TodoComment extends Comment
{
    private $todoId;

    public function __construct(TodoId $todoId, CommentId $commentId, Author $author, string $content, Collection $attachments)
    {
        parent::__construct($commentId, $author, $content, $attachments);

        $this->setTodoId($todoId);
    }

    public function todoId(): TodoId
    {
        return $this->todoId;
    }

    private function setTodoId(TodoId $todoId)
    {
        $this->todoId = $todoId;
    }
}