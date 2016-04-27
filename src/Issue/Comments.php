<?php

namespace Jira\Api\Issue;

class Comments implements \JsonSerializable
{
    /* @var int */
    public $startAt;

    /* @var int */
    public $maxResults;

    /* @var int */
    public $total;

    /* @var CommentList[\Jira\Api\Issue\Comment] */
    public $comments;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
