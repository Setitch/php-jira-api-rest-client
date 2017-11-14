<?php

namespace Jira\Api\Issue;

class IssueField implements \JsonSerializable
{
    protected $otherVals = [];
    public function __set($name, $val)
    {
        $this->otherVals[$name] = $val;
    }

    public function __get($name)
    {
        return isset($this->otherVals[$name]) ? $this->otherVals[$name] : null;
    }
    
    public function __isset($name)
    {
        return isset($this->otherVals[$name]);
    }

    
    public function getOtherVals()
    {
        return $this->otherVals;
    }

    public function __construct($updateIssue = false)
    {
        if ($updateIssue != true) {
            $this->project = new \Jira\Api\Project\Project();

            $this->assignee = new \Jira\Api\Issue\Assignee();
            $this->priority = new \Jira\Api\Issue\Priority();
            $this->versions = array();

            $this->issuetype = new \Jira\Api\Issue\IssueType();
        }
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }

    public function getProjectKey()
    {
        return $this->project->key;
    }

    public function getProjectId()
    {
        return $this->project->id;
    }

    public function setProjectKey($key)
    {
        $this->project->key = $key;

        return $this;
    }
    public function setProjectId($id)
    {
        $this->project->id = $id;

        return $this;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    public function setReporterName($name)
    {
        if (is_null($this->reporter)) {
            $this->reporter = new \Jira\Api\Issue\Reporter();
        }

        $this->reporter->name = $name;

        return $this;
    }
    
//    public function setWorklogs($wl) { $this->setWorklog($wl); }
    public function setWorklog($wl)
    {
        if (is_null($this->worklog)) {
            $this->worklog = new \Jira\Api\Issue\Worklogs();
        }
        
        $this->worklog -> maxResults = $wl->maxResults;
        $this->worklog -> startAt = $wl->startAt;
        $this->worklog -> total = $wl->total;
        $this->worklog -> addWorklog($wl->worklogs);
////        $this->worklog = $wl;
    }

    public function setAssigneeName($name)
    {
        if (is_null($this->assignee)) {
            $this->assignee = new \Jira\Api\Issue\Assignee();
        }

        $this->assignee->name = $name;

        return $this;
    }

    public function setPriorityName($name)
    {
        if (is_null($this->priority)) {
            $this->priority = new \Jira\Api\Issue\Priority();
        }

        $this->priority->name = $name;

        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function addVersion($name)
    {
        if (is_null($this->versions)) {
            $this->versions = array();
        }

        $v = new Version();
        $v->name = $name;
        array_push($this->versions, $v);

        return $this;
    }

    public function addComment($comment)
    {
        if (is_null($this->comment)) {
            $this->comment = new \Jira\Api\Issue\Comments();
        }
        
        $this->comment->comments[] = $comment;

        array_push($this->versions, $v);

        return $this;
    }
    
    public function getComments()
    {
        if (is_null($this->comment)) {
            return [];
        } else {
            return $this->comment->comments;
        }
    }

    public function addLabel($label)
    {
        if (is_null($this->labels)) {
            $this->labels = array();
        }

        array_push($this->labels, $label);

        return $this;
    }

    public function setIssueType($type)
    {
        if (is_null($this->issuetype)) {
            $this->issuetype = new \Jira\Api\Issue\IssueType();
        }

        $this->issuetype = $type;

        return $this;
    }

    public function getIssueType()
    {
        return $this->issuetype;
    }

    /** @var string */
    public $summary;

    /** @var array */
    public $progress;

    /** @var TimeTracking */
    public $timetracking;

    /** @var IssueType */
    public $issuetype;

    /** @var string */
    public $timespent;

    /** @var Reporter */
    public $reporter;

    /** @var \DateTime */
    public $created;

    /** @var \DateTime */
    public $updated;

    /** @var string */
    public $description;

    /** @var Priority */
    public $priority;

    /** @var object */
    public $status;

    /** @var array */
    public $labels;

    /** @var \Jira\Api\Project\Project */
    public $project;

    /** @var string */
    public $environment;

    /** @var array */
    public $components;

    /** @var Comments */
    public $comment;

    /** @var object */
    public $votes;

    /** @var object */
    public $resolution;

    /** @var array */
    public $fixVersions;

    /** @var Reporter */
    public $creator;

    /** @var object */
    public $watches;

    /** @var Worklogs */
    public $worklog;

    /** @var Assignee */
    public $assignee;

    /** @var \Jira\Api\Issue\Version[] */
    public $versions;

    /** @var \Jira\Api\Issue\Attachment[] */
    public $attachments;

    /** @var  string */
    public $aggregatetimespent;

    /** @var  string */
    public $timeestimate;

    /** @var  string */
    public $aggregatetimeoriginalestimate;

    /** @var  string */
    public $resolutiondate;

    /** @var \DateTime */
    public $duedate;

    /** @var array */
    public $issuelinks;

    /** @var \Jira\Api\Issue\Issue */
    public $parent;
    
    /** @var array */
    public $subtasks;

    /** @var int */
    public $workratio;

    /** @var object */
    public $aggregatetimeestimate;

    /** @var object */
    public $aggregateprogress;

    /** @var object */
    public $lastViewed;

    /** @var object */
    public $timeoriginalestimate;
}
