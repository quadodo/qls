<?php
/*** *** *** *** *** ***
 * @package Quadodo Login Script
 * @file    Entity.class.php
 * @start   October 30th, 2012
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
if (!defined('IN_SCRIPT')) {
    die('External access denied.');
}

class Entity extends Core {
    public $info;
    public $validEntity;

    public function __construct() {

    }

    /**
     * An entity doesn't require a session, so this function
     * must be called if we are accessing the current user
     * who has a session.
     */
    public function prepare() {
        $this->createSession();

        if (parent::$Session->sessionValid) {
            $q = $this->SQL->prepare('SELECT * FROM ' . DatabaseInfo::prefix . "users WHERE id={$this->userID}");
            $q->execute();

            // Let's take our results and put them in the "info" variable
            if ($q->rowCount() != 0) {
                $row = $q->fetch(PDO::FETCH_ASSOC);

                foreach ($row as $key => $value) {
                    $this->info[$key] = $value;
                }

                $this->validEntity = true;
            }
            else {
                $this->validEntity = false;
            }
        }
        else {
            $this->validEntity = false;
        }
    }

    /**
     * @param $password
     * @param $userCode
     * @return string
     */
    public static function generatePasswordHash($password, $userCode) {
        return sha1(md5($password . $userCode) . $userCode);
    }

    /**
     * @param $time
     * @param $password
     * @param $code
     * @return string
     */
    public static function generateUniqueID($time, $password, $code) {

        // We generally want the time matching in the cookie/session
        $time = (is_numeric($time)) ? sha1($time) : $time;

        return sha1($time . sha1($password . $code));
    }

    /**
     * Old and useless, uses too much memory, but good for conversion or verification
     * @param $password
     * @param $userCode
     * @return string
     */
    public static function generateOldPasswordHash($password, $userCode) {
        $hash[] = md5($password);
        $hash[] = md5($password . $userCode);
        $hash[] = md5($password) . sha1($userCode . $password) . md5(md5($password));
        $hash[] = sha1($password . $userCode . $password);
        $hash[] = md5($hash[3] . $hash[0] . $hash[1] . $hash[2] . sha1($hash[3] . $hash[2]));
        $hash[] = sha1($hash[0] . $hash[1] . $hash[2] . $hash[3]) . md5($hash[4] . $hash[4]) . sha1($userCode);
        return sha1($hash[0] . $hash[1] . $hash[2] . $hash[3] . $hash[4] . $hash[5] . md5($userCode));
    }

    public function __destruct() {

    }
}