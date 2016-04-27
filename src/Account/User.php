<?php
namespace Jira\Api\Account;


class User
{
    public $self;
    public $name;
    public $displayName;
    
    public $active;
    
    public function __construct($item) {
        $this->self = isset($item->self) ? $item->self : null;
        $this->name = isset($item->name) ? $item->name : null;
        $this->displayName = isset($item->displayName) ? $item->displayName : null;
        $this->active = isset($item->active) ? $item->active : null;
    }
}