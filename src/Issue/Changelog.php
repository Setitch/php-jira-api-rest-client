<?php
namespace Jira\Api\Issue;

class Changelog implements \JsonSerializable
{
    /**
    * @var Integer
    */
    public $id;
    /** @var Reporter */
    public $author;
    /** @var \DateTime */
    public $created;
    /** @var ChangelogsList[\Jira\Api\Issue\Changelog] */
    public $histories;
    
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
    

    public function setHistories($wl)
    {
        if (is_null($this->histories)) {
            $this->histories = [];
        }
        if (!is_array($cl)) {
            $cl = [$cl];
        }
        
        foreach ($cl as $c) {
            $this->histories[] = new \Jira\Api\Issue\Changelog($c);
        }
    }
    
//    public function 
//    public function __construct($obj) {
//        if (is_array($obj)) {}
//        elseif (is_object($obj))
//        {
//            $this->id
//            var_dump($this);
//        }
//    }
}
