<?php
namespace Jira\Api\Issue;

class Changelogs implements \JsonSerializable
{
    /* @var int */
    public $startAt;

    /* @var int */
    public $maxResults;

    /* @var int */
    public $total;

    /* @var WorklogList[\Jira\Api\Issue\Changelog] */
    public $histories;

    /**
     * @return int
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param int $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return int
     */
    public function getMaxResults()
    {
        return $this->maxResults;
    }

    /**
     * @param int $maxResults
     */
    public function setMaxResults($maxResults)
    {
        $this->maxResults = $maxResults;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }
    
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
/*    
    public function setWorklogs($wl)
    {
        if (is_null($this->worklogs)) {
            $this->worklogs = [];
        }
        if (!is_array($wl)) $wl = [$wl];
        
        foreach ($wl as $w)
            $this->worklogs[] = new \Jira\Api\Issue\Worklog($w);
    }
*/
    public function addChangelog($wl)
    {
        $this->addChangelogs($wl);
    }
    public function addChangelogs($wl)
    {
        if (is_null($this->histories)) {
            $this->histories = [];
        }
        if (!is_array($wl)) {
            $wl = [$wl];
        }
        
        foreach ($wl as $w) {
            $this->histories[] = new \Jira\Api\Issue\Changelog($w);
        }
    }
}
