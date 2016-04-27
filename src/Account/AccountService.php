<?php

namespace Jira\Api\Account;

use \Jira\Api\Account\Group;

class AccountService extends \Jira\Api\Client
{
    private $uri = '/group';

    public function addGroupUser($name, $userName)
    {
        $json = json_encode([
            'name' => $userName,
        ]);
        
        try {
            $ret = $this->exec($this->uri.'/user?groupname=' . $name, $json, 'POST');
        } catch (\Jira\Api\JIRAException $e) {
            $code = $e -> getCode();
            if ($code !== 0)
                return $code;
        }

        $group = $this->json_mapper->map(
             json_decode($ret), new Group()
        );

        return $group;
    }
    
    public function removeGroupUser($name, $userName)
    {
        
        try
        {
            $ret = $this->exec($this->uri.'/user?username='.$userName.'&groupname=' . $name, null , 'DELETE');
        }
        catch (\Jira\Api\JIRAException $e)
        {
            $code = $e -> getCode();
            return $code;
        }
        return 0;
    }
    
    /**
     * @return \Jira\Api\Account\Group[]
     */
    public function getGroup($name)
    {
        $ret = $this->exec($this->uri.'?groupname=' . $name);
//        $ret = $this->exec($this->uri.'/member?groupname=' . $name);

        $group = $this->json_mapper->map(
             json_decode($ret), new Group()
        );

        return $group;
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
        
        while ($page * $onPage < $max)
        {
            $json = json_encode([
                'groupname' => $name,
                'maxResults' => $max,
                'startAt' => $onPage * $page,
            ]);
            $ret = $this->exec($this->uri."?expand=users&maxResults=$max&startAt=".($onPage*$page).'&groupname=' . $name);
//            $ret = $this->exec($this->uri.'?groupname=' . $name);
            $ret = json_decode($ret);
            print_r($ret);
            $max = $ret->users->size;
            
            if ($group === null) {
                $group = $this->json_mapper->map(
                    ($ret), new Group()
                );
            }
            else
            {
                foreach ($ret->users->items as $item)
                    $group -> addItems($item);
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
        $json = json_encode([
            'name' => $name,
        ]);
        $ret = $this->exec($this->uri, $json, 'POST');
//        $ret = $this->exec($this->uri.'/member?groupname=' . $name);

        $group = $this->json_mapper->map(
             json_decode($ret), new Group()
        );

        return $group;
    }
}