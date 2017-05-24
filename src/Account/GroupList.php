<?php

namespace Jira\Api\Account;

class GroupList implements \JsonSerializable
{
    /**
     * @var string
     */
    public $header;

    /* @var integer */
    public $total;

    /** @var GroupElement[] */
    public $groups;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
