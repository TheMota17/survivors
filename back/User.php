<?php

    Class User {
        public function __construct($user, $hash)
        {
            $this->user = PDO2::fetch('SELECT * FROM users WHERE id = ? AND sess_hash = ?', array(intval($user), $hash));
        }

        public function getUser()
        {
            return $this->user;
        }

        public function getBanTime()
        {
            return $this->user['ban'];
        }

        public function isBanned()
        {
            if ($this->user['ban'] > time()) return true;
        }

        public function setVisitedTime()
        {
            PDO2::query('UPDATE users SET lastvisit = ? WHERE id = ?', array(time(), $this->user['id']));
        }
    }