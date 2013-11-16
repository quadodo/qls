<?php
/*** *** *** *** *** ***
* @package Quadodo Login Script
* @file    Core.class.php
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

class Core {
    public $db;
    public $plugins;
    public static $config;
    public static $Session;

    public function __construct() {

        // Let's connect to the database
        try {
            $this->db = new SQL(DatabaseInfo::type . ':host=' . DatabaseInfo::host . ';dbname=' . DatabaseInfo::name, DatabaseInfo::username, DatabaseInfo::password);
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

        // Load all the values from the database we need for the script to work
        $this->loadConfiguration();

        // Language!
        require_once('lang/' . self::$config['language'] . '.lang.php');

        // Check if we have a session
        self::$Session = new Session();

        // Load plugins
        $this->preparePlugins();

    }

    /**
     * Loads configuration into the Core class
     */
    private function loadConfiguration() {
        $q = $this->SQL->prepare('SELECT * FROM ' . DatabaseInfo::prefix . 'config');
        $q->execute();

        while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
            self::$config[$row['name']] = $row['value'];
        }
    }

    private function preparePlugins() {

    }

    protected function loadPlugin($name) {

    }

    public function createSession() {
        if (!is_null(self::$Session) && self::$Session->validateSession()) {
            self::$Session->sessionValid = true;
        }
    }

    /**
     * Redirects a user
     * @param $url
     */
    public function redirect($url) {
        switch (self::$config['redirect_type']) {

            // php
            case 0:
                header("Location: {$url}");
                break;

            // javascript
            case 1:
                echo '<script type="text/javascript"><!-- window.location="' . $url . '"; //--></script>';
                break;

            // html header
            case 2:
                echo '<html><head><meta http-equiv="refresh" value="0;' . $url . '" </head>';
                break;
        }
    }

    public function __destruct() {
        $this->SQL = null;
        self::$Session = null;
    }
}