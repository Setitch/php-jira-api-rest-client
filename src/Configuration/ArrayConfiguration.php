<?php
namespace Jira\Api\Configuration;

/**
 * Class ArrayConfiguration
 *
 * @package Jira\Api\Configuration
 */
class ArrayConfiguration extends AbstractConfiguration //implements \ArrayAccess
{
    /**
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        foreach ($configuration as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}