<?php
/*** *** *** *** *** ***
 * @package Quadodo Login Script
 * @file    Session.class.php
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

class Session extends Core {
    public $sessionFound = true;
    public $sessionValid = false;
    public $userID = null;
    public $userTime = null;
    public $userUnique = null;

    /**
     * Sees if we're using cookies or sessions and if they exist
     */
    public function __construct() {
        if (isset($_SESSION[parent::$config['cookie_prefix'] . 'user_id'])
            && isset($_SESSION[parent::$config['cookie_prefix'] . 'user_time'])
            && isset($_SESSION[parent::$config['cookie_prefix'] . 'user_unique'])) {
            $this->userID = $_SESSION[parent::$config['cookie_prefix'] . 'user_id'];
            $this->userTime = $_SESSION[parent::$config['cookie_prefix'] . 'user_time'];
            $this->userUnique = $_SESSION[parent::$config['cookie_prefix'] . 'user_unique'];
        }
        elseif (isset($_COOKIE[parent::$config['cookie_prefix'] . 'user_id'])
            && isset($_COOKIE[parent::$config['cookie_prefix'] . 'user_time'])
            && isset($_COOKIE[parent::$config['cookie_prefix'] . 'user_unique'])) {
            $this->userID = $_COOKIE[parent::$config['cookie_prefix'] . 'user_id'];
            $this->userTime = $_COOKIE[parent::$config['cookie_prefix'] . 'user_time'];
            $this->userUnique = $_COOKIE[parent::$config['cookie_prefix'] . 'user_unique'];
        }
    }

    /**
     * @return bool
     */
    public function validateSession() {
        // We need to make sure the time and unique codes are SHA1 hashes
        if (preg_match('/^[a-fA-F0-9]{40}$/', $this->userTime)
                && preg_match('/^[a-fA-F0-9]{40}$/', $this->userUnique)) {
            if (is_numeric($this->userID)) {
                $this->userID = $this->SQL->quote($this->userID);
                $this->userTime = $this->SQL->quote($this->userTime);
                $this->userUnique = $this->SQL->quote($this->userUnique);

                $q = $this->SQL->prepare('SELECT value,time FROM ' . DatabaseInfo::prefix  . "sessions WHERE user_id={$this->userID}");
                $q->execute();

                $row = $q->fetch(PDO::FETCH_ASSOC);
                if ($row['value'] == $this->userUnique && sha1($row['time']) == $this->userTime) {
                    return true;
                }
            }
        }

        return false;
    }

    public function start($userID, $userPassword, $userCode, $remember) {
        $time = time();
        $uniqueValue = Entity::generateUniqueID($time, $userPassword, $userCode);

        // Delete any previous session information
        $q = $this->SQL->prepare('DELETE FROM ' . DatabaseInfo::prefix . "sessions WHERE user_id={$userID}");
        $q->execute();

        $q = null;

        // Create a new session (query)
        $newSession = 'INSERT INTO ' . DatabaseInfo::prefix . 'sessions (user_id,value,time) ';
        $newSession .= "VALUES({$userID},'{$uniqueValue}'," . $time . ')';

        $q = $this->SQL->prepare($newSession);
        $q->execute();

        if ($remember == '1') {
            $_COOKIE[parent::$config['cookie_prefix'] . 'user_id'] = $userID;
            $_COOKIE[parent::$config['cookie_prefix'] . 'user_time'] = sha1($time);
            $_COOKIE[parent::$config['cookie_prefix'] . 'user_unique'] = $uniqueValue;
        }
        else {
            $_SESSION[parent::$config['cookie_prefix'] . 'user_id'] = $userID;
            $_SESSION[parent::$config['cookie_prefix'] . 'user_time'] = sha1($time);
            $_SESSION[parent::$config['cookie_prefix'] . 'user_unique'] = $uniqueValue;
        }
    }

    public function __destruct() {

    }
}