<?php
namespace Jira\Api\Account;

class GroupElement //implements \JsonSerializable
{
    /** @var string */
    public $name;
    /** @var string */
    public $html;
    /** @var string[] */
    public $labels;
}
