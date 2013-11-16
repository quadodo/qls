<?php
/*** *** *** *** *** ***
 * @package Quadodo Login Script
 * @file    register.process.php
 * @start   November 5nd, 2012
 * @author  Douglas Rennehan
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @version 1.0.0
 * @site    http://www.quadodo.net
 *** *** *** *** *** ***
 * Copyright (C) 2012 Douglas Rennehan
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *** *** *** *** *** ***
 * Comments are always before the code they are commenting.
 *** *** *** *** *** ***/
require_once('../includes/header.php');

if (isset($_POST['process']) && $_POST['process'] == 'true') {
    $username = (isset($_POST['username'])) ? $Core->SQL->quote($_POST['username']) : false;
    $password = (isset($_POST['password'])) ? $Core->SQL->quote($_POST['password']) : false;
    $passwordConfirm = (isset($_POST['passwordConfirm'])) ? $Core->SQL->quote($_POST['passwordConfirm']) : false;
    $email = (isset($_POST['email'])) ? $Core->SQL->quote($_POST['email']) : false;
    $emailConfirm = (isset($_POST['emailConfirm'])) ? $Core->SQL->quote($_POST['emailConfirm']) : false;

    // Let's do some error handling
    $errors[] = array();

    // Username must not be empty, and must be within bounds
    if ($username === false) {
        $errors[] = USERNAME_EMPTY;
    }

    // Username must be correct size
    if (strlen($username) < Core::$config['minUsername']
        || strlen($username) > Core::$config['maxUsername']) {
        $errors[] = USERNAME_WRONG_LENGTH;
    }

    // Passwords must match and be within bounds
    if ($password != $passwordConfirm) {
        $errors[] = PASSWORDS_DONT_MATCH;
    }

    // Passwords must be the correct size
    if (strlen($password) < Core::$config['minPassword']
        || strlen($password) > Core::$config['maxPassword']) {
        $errors[] = PASSWORD_WRONG_LENGTH;
    }

    // Emails must match and be within bounds
    if ($email !== $emailConfirm) {
        $errors[] = EMAILS_DONT_MATCH;
    }

    // Email must be the correct size
    if (strlen($email) < 7 || strlen($email) > 255) {
        $errors[] = EMAIL_WRONG_LENGTH;
    }

    // Email must match the following RegEx
    ///^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
    if (preg_match(Core::$config['emailStruct'], $email, $matches) === false) {
        $errors[] = EMAIL_WRONG_FORMAT;
    }

    // Did anything go wrong?
    if (count($errors) > 0) {

        // Store them in the session to show the user on the register page
        foreach ($errors as $key => $value) {
            if (!is_array($value)) {
                $_SESSION['errors'] .= '<br />' . $value;
            }
        }

        $Core->redirect('../register.php');
        exit;
    }
    else {
        // Edit the following code if the columns should change
        $insertColumns = array(
            'username',
            'password',
            'email'
        );

        $insertValues = array(
            $username,
            $password,
            $email
        );



        $q = $Core->SQL->prepare('INSERT INTO ' . DatabaseInfo::prefix . ' (' . $string . ') VALUES(');
    }
}
else {
    $Core->redirect('../register.php');
}