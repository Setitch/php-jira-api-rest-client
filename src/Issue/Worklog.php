<?php
namespace Jira\Api\Issue;

class Worklog implements \JsonSerializable
{
    public $self;
    public $author;
    public $updateAuthor;
    public $comment;
    public $created;
    public $updated;
    public $started;
    public $timeSpent;
    public $timeSpentSeconds;
    public $id;
    public $issueid;
    
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
    
    public function __construct($obj)
    {
        if (is_array($obj)) {
        } elseif (is_object($obj)) {
            $this->id = $obj->id;
            $this->self = $obj->self;
            $this->author = $obj->author;
            $this->updateAuthor = $obj->updateAuthor;
            $this->comment = $obj -> comment;
            $this->created = $obj->created;
            $this->updated = $obj->updated;
            $this->started = $obj->started;
            $this->timeSpent = $obj->timeSpent;
            $this->timeSpentSeconds = $obj->timeSpentSeconds;
            if (isset($obj->issueid)) {
                $this->issueid = $obj -> issueid;
            }
        }
    }
}
