<?php
namespace Jira\Api\Issue;

/**
* Class to use for creating (only) subtasks
*
* @author Artur (Seti) Łabudziński
*/
class SubIssueField extends IssueField
{
    public $parent = [];

    public function __construct($parentID = null)
    {
        parent::__construct(false);
        if (is_numeric($parentID)) $parentID += 0;

        if (is_int($parentID)) $this->parent['id'] = (string)$parentID;
        else
        {
            throw new \InvalidArgumentException('Parent Issue ID must be INT');
        }
    }
}
