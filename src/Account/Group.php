<?php
namespace Jira\Api\Account;


class Group // implements \JsonSerializable
{
    public $name;
    public $self;
    public $users = null;
    
    public function setUsers($users) {
        if ($this->users === null)
            $this->users = new \Jira\Api\Account\Users($users);
    }
    /** @var string */
    public $expand;
}