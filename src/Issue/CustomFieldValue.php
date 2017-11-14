<?php
namespace Jira\Api\Issue;

/**
* Class to use for setting values of customfields that have complicated values
*
* @author Artur (Seti) Łabudziński
*/
class CustomFieldValue implements \JsonSerializable
{
    protected $values = [];
    
    public function __construct($value = null)
    {
        if ($value) {
            $this->__set('value', $value);
        }
    }
    
    public function __set($name, $value)
    {
        $this->values[$name] = $value;
    }

    public function __get($name)
    {
        $ret = isset($this->values[$name]) ? $this->values[$name] : [];
        
        return $ret;
    }

    public function jsonSerialize()
    {
        $ret = array_filter(get_object_vars($this));
        if (isset($ret['values'])) {
            foreach ($ret['values'] as $key => $val) {
                $ret[$key] = $val;
            }
            unset($ret['values']);
        }
        return $ret;
    }
}
