<?php
    require realpath('../db.php');
    require realpath('../Utils.php');

    Class Costumize {

        public function __construct($Pdo)
        {

            $this->pdo = $Pdo;

        }

        public function userNotCostumized($user)
        {
            if ($user['costumize'] == 0) return true;
        }

        public function costumizeUser($user, $hair, $beard, $cloth, $pants, $fwear)
        {
            $this->pdo->query('UPDATE `nadeto` SET `hair` = ?, `beard` = ?, `cloth` = ?, `pants` = ?, `fwear` = ? WHERE `user_id` = ?',array(
                $hair, $beard, $cloth, $pants, $fwear, $user['id']
            ));

            $this->pdo->query('UPDATE `users` SET `costumize` = ? WHERE `id` = ?', array(1, $user['id']));
        }

        public function locateUserToPage($page)
        {
            exit(json_encode(['page' => $page, 'token' => isset($_SESSION['token']) ]));
        }

        public function answer($type, $message)
        {
            switch($type)
            {
                case 'mess':
                    exit(json_encode( ['message' => $message, 'popup' => true] ));
                break;
            }
        }

        public function main()
        {
            $hair  = htmlspecialchars( intval( $_POST['hair'] ) );
            $beard = htmlspecialchars( intval( $_POST['beard'] ) );
            $cloth = htmlspecialchars( intval( $_POST['cloth'] ) );
            $pants = htmlspecialchars( intval( $_POST['pants'] ) );
            $fwear = htmlspecialchars( intval( $_POST['fwear'] ) );

            $user = $this->pdo->fetch('SELECT * FROM `users` WHERE `id` = ?', array($_SESSION['user']));

            if ($this->userNotCostumized($user))
            {
                $this->costumizeUser($user, $hair, $beard, $cloth, $pants, $fwear);
            }

            $this->answer('page', '/');
        }
    }

    if ($Utils::checkSession() && $Utils::checkToken())
    {
        $Costumize = new Costumize($Pdo);
        $Costumize->main();
    }