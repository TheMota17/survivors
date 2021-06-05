<?php

    class PDO2 {
        public static $db;

        public static function query($query, $args = [])
        {
            try
            {
                $statement = PDO2::$db->prepare($query);
                $statement->execute($args);
            } catch (PDOException $e)
            {
                throw $e;
            }
            return $statement;
        }

        public static function rows($query, $args = [])
        {
            return PDO2::query($query, $args)->rowCount();
        }

        public static function fetch($query, $args = [])
        {
            $stmt = PDO2::query($query, $args);
            return $data = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        public static function fetchAll($query, $args = [])
        {
            $stmt = PDO2::query($query, $args);
            return $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function noPrepared($query)
        {
            return PDO2::$db->query( $query );
        }

        public static function quote($var)
        {
            return $var = PDO2::$db->quote($var);
        }

        public static function last()
        {
            return PDO2::$db->lastInsertId();
        }

        public static function offEmulate()
        {
            PDO2::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }

        public static function Emulate($param)
        {
            return PDO2::onEmulate($param);
        }

        public static function onEmulate($param)
        {
            PDO2::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, $param);
        }
    }

    Class GenRandCodes {
        public static function sessHash()
        {
            $alphabet    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            $token       = array();
            $alphaLength = strlen($alphabet) - 1;

            for ($i = 0; $i < 17; $i++)
            {
                $n       = rand(0, $alphaLength);
                $token[] = $alphabet[ $n ];
            }

            return implode($token);
        }

        public static function authCode()
        {
            $alphabet    = 'abcdefghijklmnopqrstuvwxyz';
            $code        = array();
            $alphaLength = strlen($alphabet) - 1;

            for($i = 0; $i < 4; $i++)
            {
                $n       = rand(0, $alphaLength);
                $code[]  = $alphabet[ $n ];
            }

            return implode($code);
        }
    }

    Class CheckClient {
        public static function cookies()
        {
            if (isset($_COOKIE['user']) && isset($_COOKIE['hash'])) return true;
        }
    }

    Class Messenger {
        public static function sendMessage($message, $popup, $page)
        {
            exit(json_encode(
                    array_merge($message, [
                    'moves' => [
                        'popup' => $popup,
                        'page' => $page
                    ]
                ])
            ));
        }
    }