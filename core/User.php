<?php
    require 'db.php';
    require 'Utils.php';

    Class User {
        private $pdo;

        private $message;

        private $user;
        private $id;

        public function __construct($pdo, $id)
        {

            $this->pdo = $pdo;

            $this->user = $this->pdo->fetch('SELECT * FROM `users` WHERE `id` = ?', array( intval( htmlspecialchars( $id ) ) ));

        }

        public function getUser()
        {
            return $this->user;
        }

        public function getGame()
        {
            return [
                'loc' => $this->user['loc'],
                'loc_explored' => $this->user['loc_explored'],
                'x' => $this->user['x'],
                'y' => $this->user['y'],
                'hp' => $this->user['hp'],
                'hung' => $this->user['hung'],
                'thirst' => $this->user['thirst'],
                'fatigue' => $this->user['fatigue'],
                'speed' => $this->user['speed'],
                'time' => $this->user['time'],
                'weather' => $this->user['weather'],
                'temp' => $this->user['temp']
            ];
        }

        public function userInfo($type, $data)
        {
            switch($type)
            {
                case 'userinfo':
                    return $this->user[ $data ];
                break;
            }
        }

        public function ban()
        {
            if ($this->user['ban'] > time())
            {
                $this->message = date('d.m.Y H:i:s', $this->user['ban']);
                $this->answer('ban');
            } else
            {
                return true;
            }
        }

        public function userCostumized()
        {
            if ($this->user['costumize'] == 0)
            {
                $this->locateUserToPage('/costumize');
            }
        }

        public function visit()
        {
            $this->pdo->query('UPDATE users SET `lastvisit` = ? WHERE `id` = ?', array(time(), $this->user['id']));
        }

        public function locateUserToPage($page)
        {
            exit(json_encode(['page' => $page, 'token' => isset($_SESSION['token']) ]));
        }

        public function answer( $move )
        {
            switch( $move)
            {
                case 'costumize':
                    exit( json_encode( ['page' => 'costumize'] ) );
                break;
                case 'ban':
                    exit( json_encode( ['message' => 'Вы заблокированы до - '.$this->message.'', 'popup' => true] ) );
                break;
            }
        }

        public function main()
        {
            if (!$this->user)
            {
                $this->locateUserToPage('/auth');
            } else
            {
                $this->ban();
                $this->userCostumized();
                $this->visit();
            }
        }
    }

    if ($Utils::checkSession() && $Utils::checkToken())
    {
        $User = new User($Pdo, $_SESSION['user']);
        $User->main();
    }