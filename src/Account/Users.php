<?php
namespace Jira\Api\Account;

class Users
{
    /* @var int */
    public $size;
    /* @var UserList[\Jira\Api\Account\User] */
    public $items;
    
    public function setItems($items)
    {
        if (!is_array($this->items)) {
            $this->items = [];
        }
        
        foreach ($items as $item) {
            $this->items[] = new \Jira\Api\Account\User($item);
        }
    }
    
    public function __construct($obj)
    {
        $this->size = isset($obj->size) ? $obj->size : 0;
        $this->setItems(isset($obj->items) ? $obj->items : []);
    }
}
