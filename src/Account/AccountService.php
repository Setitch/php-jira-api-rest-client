<?php

namespace Jira\Api\Account;

use \Jira\Api\Account\Group;
use \Jira\Api\Account\GroupList;

class AccountService extends \Jira\Api\Client
{
    private $uri = '/group';

    public function addGroupUser($name, $userName)
    {
        $json = json_encode(
            [
            'name' => $userName,
            ]
        );
        
        try {
            if (self::$isJIRAUtf8) {
                $ret = $this->exec($this->uri.'/user?groupname=' . $this->filterName(urlencode($name), '+').'&useUnicode=true&characterEncoding=unicode', $json, 'POST');
            } else {
                $ret = $this->exec($this->uri.'/user?groupname=' . $this->filterName(urlencode($name), '+').'', $json, 'POST');
            }
        } catch (\Jira\Api\Exception $e) {
            $code = $e -> getCode();
            $err = $e -> getMessage();
            if (false !== strpos($err, "Cannot add user. '$userName' does not exist")) {
                return -1;
            }
            
            if (false !== strpos($err, "Cannot add user '$userName', user is already a member of '$name'")) {
                return -2;
            }
            
            if ($code !== 0) {
                return $code;
            }
        }

        $group = $this->json_mapper->map(
            json_decode($ret),
            new Group()
        );

        return $group;
    }
    
    public function removeGroupUser($name, $userName)
    {
        
        try {
            if (self::$isJIRAUtf8) {
                $ret = $this->exec($this->uri.'/user?username='.$userName.'&groupname=' . $this->filterName(urlencode($name), '+').'&useUnicode=true&characterEncoding=unicode', null, 'DELETE');
            } else {
                $ret = $this->exec($this->uri.'/user?username='.$userName.'&groupname=' . $this->filterName(urlencode($name), '+').'', null, 'DELETE');
            }
        } catch (\Jira\Api\Exception $e) {
            $code = $e -> getCode();
            $err = $e -> getMessage();

            if (false !== strpos($err, "User '$userName' does not exist")) {
                return -1;
            }
            
            if (false !== strpos($err, "Cannot remove user '$userName' from group '$name' since user is not a member of '$name'")) {
                return -2;
            }

            return $code;
        }
        return 0;
    }
    
    /**
     * @return \Jira\Api\Account\Group[]
     */
    public function getGroup($name)
    {
        if (self::$isJIRAUtf8) {
            $ret = $this->exec($this->uri.'?groupname=' . str_replace(' ', '+', urlencode($name)).'&useUnicode=true&characterEncoding=unicode');
        } else {
            $ret = $this->exec($this->uri.'?groupname=' . str_replace(' ', '+', urlencode($name)));// .'&useUnicode=false&characterEncoding=unicode');
        }
//        $ret = $this->exec($this->uri.'?groupname=' . ($this->filterName($name,'+')) .'&useUnicode=true&characterEncoding=UTF8');
//        $ret = $this->exec($this->uri, $json, 'POST');
//        $ret = $this->exec($this->uri.'/member?groupname=' . $name);
//var_dump($ret);

        $group = $this->json_mapper->map(
            json_decode($ret),
            new Group()
        );
        return $group;
    }
    
    public function findGroups($query = '')
    {
        if (self::$isJIRAUtf8) {
            $ret = $this->exec($this->uri.'s/picker?maxResults=1&'.($query?('query=' . str_replace(' ', '+', urlencode($query)).'&useUnicode=true&characterEncoding=unicode'):''));
        } else {
            $ret = $this->exec($this->uri.'s/picker?maxResults=1&'.($query?('query=' . str_replace(' ', '+', urlencode($query))):''));// .'&useUnicode=false&characterEncoding=unicode');
        }
//        $ret = $this->exec($this->uri.'?groupname=' . ($this->filterName($name,'+')) .'&useUnicode=true&characterEncoding=UTF8');
//        $ret = $this->exec($this->uri, $json, 'POST');
//        $ret = $this->exec($this->uri.'/member?groupname=' . $name);
//var_dump($ret);

        $groups = $this->json_mapper->map(
            json_decode($ret),
            new GroupList()
        );

        if (self::$isJIRAUtf8) {
            $ret = $this->exec($this->uri.'s/picker?maxResults='.$groups->total.'&'.($query?('query=' . str_replace(' ', '+', urlencode($query)).'&useUnicode=true&characterEncoding=unicode'):''));
        } else {
            $ret = $this->exec($this->uri.'s/picker?maxResults='.$groups->total.'&'.($query?('query=' . str_replace(' ', '+', urlencode($query))):''));// .'&useUnicode=false&characterEncoding=unicode');
        }
//        $ret = $this->exec($this->uri.'?groupname=' . ($this->filterName($name,'+')) .'&useUnicode=true&characterEncoding=UTF8');
//        $ret = $this->exec($this->uri, $json, 'POST');
//        $ret = $this->exec($this->uri.'/member?groupname=' . $name);
//var_dump($ret);

        $groups = $this->json_mapper->map(
            json_decode($ret),
            new GroupList()
        );


        return $groups;
    }
    
    /**
     * @return \Jira\Api\Account\Group[]
     */
    public function getGroupMembers($name)
    {
        $group = null;
        $page = 0;
        $onPage = 1000;
        $max = 10000;
        
        while ($page * $onPage < $max) {
            $json = json_encode(
                [
                //                'groupname' => $this->filterName($name),
                'maxResults' => $max,
                'startAt' => $onPage * $page,
                ]
            );
            if (self::$isJIRAUtf8) {
                $ret = $this->exec($this->uri."?expand=users&maxResults=$max&startAt=".($onPage*$page).'&groupname=' . $this->filterName(urlencode($name), '+').'&useUnicode=true&characterEncoding=unicode');
            } else {
                $ret = $this->exec($this->uri."?expand=users&maxResults=$max&startAt=".($onPage*$page).'&groupname=' . $this->filterName(urlencode($name), '+'));
            }
//            $ret = $this->exec($this->uri.'?groupname=' . $name);
            $ret = json_decode($ret);
//            print_r($ret);
            $max = $ret->users->size;
            
            if ($group === null) {
                $group = $this->json_mapper->map(
                    ($ret),
                    new Group()
                );
            } else {
                foreach ($ret->users->items as $item) {
                    $group -> addItems($item);
                }
            }
            ++$page;
        }

        return $group;
    }

    /**
     * @return \Jira\Api\Account\Group[]
     */
    public function createGroup($name)
    {
        $json = json_encode(
            [
            'name' => $this->filterName($name),
            ]
        );
//        var_dump($json);
        try {
            $ret = $this->exec($this->uri, $json, 'POST');
        } catch (\Exception $e) {
            if (stripos($e->getMessage(), 'A group or user with this name already exists.') !== 0) {
                throw $e;
            } else {
                throw $e;
            }
        }

        $group = $this->json_mapper->map(
            json_decode($ret),
            new Group()
        );

        return $group;
    }
    
    public function removeGroup($name)
    {
        $json = json_encode(
            [
            'groupname' => $this->filterName($name),
            'name' => $this->filterName($name),
            'group' => $this->filterName($name),
            ]
        );
        $ret = false;
        try {
            $ret = $this->exec($this->uri . '?groupname='.str_replace(' ', '+', urlencode($name)).'&useUnicode=true&characterEncoding=unicode', null, 'DELETE');
        } catch (\Exception $e) {
            return false;
//            var_dump($e->getMessage());
        }
        
        return $ret;
    }
}
