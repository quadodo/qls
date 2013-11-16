<?php
if (!defined('IN_SCRIPT')) {
    die('External access denied.');
}

define('LOGIN_LINK', '<a href="/login.php">login</a>');
define('LOGOUT_LINK', '<a href="/logout.php">logout</a>');

define('USERNAME_EMPTY', 'A username was not entered');
define('USERNAME_WRONG_LENGTH', 'The username entered was either too long, or too short');
define('USERNAME_NOT_FOUND', 'The username requested could not be found in the database');

define('PASSWORD_EMPTY', 'A password was not entered');
define('PASSWORD_WRONG_LENGTH', 'The password entered was either too long or too short');
define('PASSWORD_WRONG', 'The password you entered was wrong');
define('PASSWORDS_DONT_MATCH', 'The passwords you entered do not match');

define('EMAILS_DONT_MATCH', 'The emails you entered do not match');
define('EMAIL_WRONG_LENGTH', 'The email you entered was either too long or too short');
define('EMAIL_WRONG_FORMAT', 'The email you entered doesn\'t match the required format');

define('ALREADY_REGISTERED', 'You have already registered an account! Please ' . LOGOUT_LINK . ' to register a new one.');
define('ALREADY_LOGGED_IN', 'You are currently logged in, please ' . LOGOUT_LINK . ' to login again.');

// Software Error Handling
define('QLS_FATAL_SQL_MISMATCH', 'SQL Mismatch: The number of columns for insertion did not match the values.');