<?php
namespace Jira\Api\Configuration;

/**
 * Class AbstractConfiguration
 *
 * @package Jira\Api\Configuration
 */
abstract class AbstractConfiguration implements \Jira\Api\Configuration\ConfigurationInterface
{
    /**
     * Jira host
     *
     * @var string
     */
    protected $jiraHost;

    /**
     * Jira login
     *
     * @var string
     */
    protected $jiraUser;

    /**
     * Jira password
     *
     * @var string
     */
    protected $jiraPassword;

    /**
     * Path to log file
     *
     * @var string
     */
    protected $jiraLogFile;

    /**
     * Log level (DEBUG, INFO, ERROR, WARNING)
     *
     * @var string
     */
    protected $jiraLogLevel;

    /**
     * Curl options CURLOPT_SSL_VERIFYHOST
     *
     * @var boolean
     */
    protected $curlOptSslVerifyHost;

    /**
     * Curl options CURLOPT_SSL_VERIFYPEER
     *
     * @var boolean
     */
    protected $curlOptSslVerifyPeer;

    /**
     * Curl options CURLOPT_VERBOSE
     *
     * @var boolean
     */
    protected $curlOptVerbose;

    protected $mapper = null;
    
    protected $utf8support = false;
    
    /**
    * Name visible in userAgent (additional data apart from Library version).
    *
    * @var string
    */
    protected $userAgent = '';

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }
    
    /**
     * @return string
     */
    public function getJiraHost()
    {
        return $this->jiraHost;
    }

    /**
     * @return string
     */
    public function getJiraUser()
    {
        return $this->jiraUser;
    }

    /**
     * @return string
     */
    public function getJiraPassword()
    {
        return $this->jiraPassword;
    }

    /**
     * @return string
     */
    public function getJiraLogFile()
    {
        return $this->jiraLogFile;
    }

    /**
     * @return string
     */
    public function getJiraLogLevel()
    {
        return $this->jiraLogLevel;
    }

    /**
     * @return boolean
     */
    public function isCurlOptSslVerifyHost()
    {
        return $this->curlOptSslVerifyHost;
    }

    /**
     * @return boolean
     */
    public function isCurlOptSslVerifyPeer()
    {
        return $this->curlOptSslVerifyPeer;
    }

    /**
     * @return boolean
     */
    public function isCurlOptVerbose()
    {
        return $this->curlOptVerbose;
    }

    public function getMapper()
    {
        return $this->mapper;
    }
    
    public function getUtf8Support()
    {
        return $this->utf8support;
    }
}
