<?php
/*** *** *** *** *** ***
 * @package Quadodo Login Script
 * @file    login.process.php
 * @start   November 2nd, 2012
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
require_once('./includes/header.php');

if (isset($_POST['process']) && $_POST['process'] == 'true') {
    $errors = array();

    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = $_POST['remember'];

    if (empty($username)) {
        $errors[0] = USERNAME_EMPTY;
    }

    if (empty($password)) {
        $errors[1] = PASSWORD_EMPTY;
    }

    if (strlen($username) > Core::$config['max_username'] || strlen($username) < Core::$config['min_username']) {
        $errors[2] = USERNAME_WRONG_LENGTH;
    }

    if (strlen($password) > Core::$config['max_password'] || strlen($password) < Core::$config['min_password']) {
        $errors[3] = PASSWORD_WRONG_LENGTH;
    }

    // Let's check these now before we move on to database manipulation
    if (count($errors) > 0) {
        foreach ($errors as $key => $value) {
            $errorString .= $key;
        }

        $Core->redirect('./login.php?r=' . $errorString);
    }

    $username = $Core->SQL->quote($username);
    $password = $Core->SQL->quote($password);

    $q = $Core->SQL->prepare('SELECT id,password,code FROM ' . DatabaseInfo::prefix . "users WHERE username='{$username}'");
    $q->execute();

    if ($q->rowCount() > 0) {
        $row = $q->fetch(PDO::FETCH_ASSOC);

        if (Entity::generatePasswordHash($password, $row['code']) == $row['password']) {
            $Core->Session->start($id, $password, $row['code'], $remember);
            $Core->redirect(Core::$config['login_redirect']);
        }
        else {
            $errors[4] = PASSWORD_WRONG;
        }
    }
    else {
        $errors[5] = USERNAME_NOT_FOUND;
    }
}
else {
    $Core->redirect('./login.php');
}