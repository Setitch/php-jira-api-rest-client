<?php

namespace Jira\Api\Issue;

class Assignee implements \JsonSerializable
{
    /* @var string */
    public $self;

    /* @var string */
    public $name;

    /* @var string */
    public $key;

    /* @var string */
    public $emailAddress;

    /* @var string */
    public $avatarUrls;

     /* @var string */
    public $displayName;

     /* @var string */
    public $active;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
