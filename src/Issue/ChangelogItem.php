<?php
namespace Jira\Api\Issue;

class ChangelogItem implements \JsonSerializable
{
    /** @var string */
    public $field;
    /** @var string */
    public $fieldType;
    /** @var string */
    public $from;
    /** @var string */
    public $fromString;
    /** @var string */
    public $to;
    /** @var string */
    public $toString;
    
    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
    
    public function __construct($obj) {
        if (is_array($obj)) {}
        elseif (is_object($obj))
        {
            var_dump($obj);
            $this->field = $obj->field;
            $this->fieldType = $obj->fieldType;
            $this->from = $obj->from;
            $this->fromString = $obj->fromString;
            $this->to = $obj -> to;
            $this->toString = $obj->toString;
        }
    }
}