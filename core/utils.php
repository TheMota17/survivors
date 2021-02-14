<?php

    Class Utils {

        public static function verifMail($mail) {

            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) return true;

        }

        public static function verifName($name) {

            if (preg_match('/^[a-zA-Z0-9_]{3,14}$/', $name)) return true;

        }

        public static function verifPass($pass) {

            if (preg_match('/^[\da-zA-Z0-9_]{3,}$/', $pass)) return true;

        }

        public static function convertTime($time) {

            $minutes = floor($time / 60);
            $hours   = floor($minutes / 60);
            $minutes = $minutes - ($hours * 60);

            if ($hours < 10) {
                if ($minutes < 10) return '0'.$hours.':0'.$minutes.'';
                else return '0'.$hours.':'.$minutes.'';
            } else {
                if ($minutes < 10) return ''.$hours.':0'.$minutes.'';
                else return ''.$hours.':'.$minutes.'';
            }

        }

        public static function convertSecs($secs) {

            $minutes = floor($secs / 60);
            $hours   = floor($minutes / 60);
            $minutes = $minutes - ($hours * 60);

            if ($hours < 10) $hours = '0'.$hours;
            if ($minutes < 10) $minutes = '0'.$minutes;

            if ($hours == 0) {
                return $hours.':'.$minutes.' м.';
            } else {
                return $hours.':'.$minutes.' ч.';
            }

        }

        public static function token() {

            $alphabet    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $token       = array();
            $alphaLength = strlen($alphabet) - 1;
            
            for ($i = 0; $i < 8; $i++) {
                $n       = rand(0, $alphaLength);
                $token[] = $alphabet[$n];
            }

            return implode($token);

        }

        public static function authToken() {

            $alphabet    = 'abcdefghijklmnopqrstuvwxyz';
            $token       = array();
            $alphaLength = strlen($alphabet) - 1;

            for($i = 0; $i < 4; $i++) {
                $n       = rand(0, $alphaLength);
                $token[] = $alphabet[$n];
            }

            return implode($token);

        }

        public static function checkSession() {

            if ($_SESSION['user'] && $_SESSION['token'] == $_POST['token'] && $_POST['token'] && $_SESSION['token']) {
                return true;
            } else {
                exit(json_encode( ['page' => 'auth'] ));
            }

        }

    }

    $Utils = new Utils();