<?php
/**
 * Created by PhpStorm.
 * User: AP_514
 * Date: 2017-11-14
 * Time: 11:43
 */

namespace Jira\Api\ScriptRunner;

class Custom extends \Jira\Api\Client
{
    /**
     * JIRA REST API URI
     *
     * @var string
     */
    private $api_uri = '/rest/scriptrunner/latest/custom';

    /**
     * Get URL by context
     *
     * @param string $context
     *
     * @return string
     */
    protected function createUrlByContext($context)
    {
        $host = $this->getConfiguration()->getJiraHost();

        if (substr($context, 0, 1) === '*') {
            $context = substr($context, 1);

            return $host . '/rest' . '/' . preg_replace('/\//', '', $context, 1);
        }

        return $host . $this->api_uri . '/' . preg_replace('/\//', '', $context, 1);
    }

    /**
     * Execute REST request
     *
     * @param string $context Rest API context (ex.:issue, search, etc..)
     * @param null $post_data
     * @param null $custom_request
     *
     * @return array|object
     * @throws Exception
     */
    public function exec($context, $post_data = null, $custom_request = null, $tries = 3)
    {
        $ret = parent::exec($context, $post_data, $custom_request, $tries);
        if ($ret) {
            return json_decode($ret);
        }

        return $ret;
    }
}
