<?php
namespace Jira\Api\Issue;

/**
* Class to use for updating customfields
*
* @author Artur (Seti) Łabudziński
*/
class IssueFieldWithCustoms extends \Jira\Api\Issue\IssueField
{
    public function setCustom($name, $value)
    {
        if (is_numeric($name)) {
            $this->otherVals['customfield_'.$name] = $value;
        }
    }

    public function __get($name)
    {
        if (is_numeric($name)) {
            $ret = isset($this->otherVals['customfield_'.$name]) ? $this->otherVals['customfield_'.$name] : [];
        } else {
            $ret = isset($this->otherVals[$name]) ? $this->otherVals[$name] : [];
        }
        return $ret;
    }

    public function jsonSerialize()
    {

        $ret = array_filter(get_object_vars($this));
        if (isset($ret['otherVals'])) {
            foreach ($ret['otherVals'] as $key => $val) {
                $ret[$key] = $val;
            }
            unset($ret['otherVals']);
        }
        return $ret;
    }
}
