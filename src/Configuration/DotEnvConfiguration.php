<?php
/**
 * Created by PhpStorm.
 * User: keanor
 * Date: 17.08.15
 * Time: 22:01
 */
namespace Jira\Api\Configuration;

use Dotenv;

/**
 * Class DotEnvConfiguration
 *
 * @package Jira\Api\Configuration
 */
class DotEnvConfiguration extends AbstractConfiguration
{
    /**
     * @param string $path
     */
    public function __construct($path = '.')
    {
        Dotenv::load($path);

        // not available in dotenv 1.1
        // $dotenv->required(['JIRA_HOST', 'JIRA_USER', 'JIRA_PASS']);

        $this->jiraHost = $this->env('JIRA_HOST');
        $this->jiraUser = $this->env('JIRA_USER');
        $this->jiraPassword = $this->env('JIRA_PASS');
        $this->jiraLogFile = $this->env('JIRA_LOG_FILE', 'jira-rest-client.log');
        $this->jiraLogLevel = $this->env('JIRA_LOG_LEVEL', 'WARNING');
        $this->curlOptSslVerifyHost = $this->env('CURLOPT_SSL_VERIFYHOST', false);
        $this->curlOptSslVerifyPeer = $this->env('CURLOPT_SSL_VERIFYPEER', false);
        $this->curlOptVerbose = $this->env('CURLOPT_VERBOSE', false);
        $this->userAgent = $this->env('USER_AGENT', '');
    }

    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    private function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return null;
        }

        if ($this->startsWith($value, '"') && endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public function startsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public function endsWith($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === substr($haystack, -strlen($needle))) {
                return true;
            }
        }

        return false;
    }
}
