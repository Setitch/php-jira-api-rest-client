
# Installation

Based on https://github.com/lesstif/php-jira-rest-client - modified by Seti the Dragon.
Documentation in progress but most usability is left intact.

# Differences
* namespace \Jira\Api
* Modified JsonMapper class (extended) so customfields can be used.
* More data fetched properly from Jira API. [comments]
* Using Rotating Files instead of Stream logging.
* Added
* -  Change Issue Labels
* -  Fetching WorkLogs
* -  It is now possible to save custom fields with this project.
* -  Function for fixing dates (changing timezone for fetching correct date in php).
* - -  Accounts
* - * Adding and Removing Users from Groups (Creating groups too).

* Fixes
* * Timeout stopping whole scripts (15 sec for connection, 45 for data);
* * Retries of execution of curl (3);


# TODO
* Cleaning the Code
* More Services
* Tests
