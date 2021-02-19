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

            $this->user = $this->pdo->fetch('SELECT * FROM `users` WHERE `id` = ?', array( htmlspecialchars( intval( $id ) ) ));
            $this->game = json_decode( $this->user['game'] );

        }

        public function getUser() {

            return $this->user;

        }

        public function getGame() {

            return $this->game;

        }

        public function userInfo($type, $data) {

            switch($type) {
                case 'userinfo':
                    return $this->user[ $data ];
                break;
            }

        }

        public function ban() {

            if ($this->user['ban'] > time()) {
                $this->message = date('d.m.Y H:i:s', $this->user['ban']);
                $this->exit( 'ban' );
            } else {
                return true;
            }

        }

        public function costumize() {

            if ($this->user['costumize'] > 0) return true;
            else $this->exit('costumize');

        }

        public function visit() {

            $this->pdo->query('UPDATE users SET `lastvisit` = ? WHERE `id` = ?', array(time(), $this->user['id']));

        }
 
        public function exit( $move ) {

            switch( $move) {
                case 'costumize':
                    exit( json_encode( ['page' => 'costumize'] ) );
                break;
                case 'ban':
                    exit( json_encode( ['message' => 'Вы заблокированы до - '.$this->message.'', 'popup' => true] ) );
                break;
            }

        }

        public function main() {
            
            $this->ban();
            $this->costumize();
            $this->visit();

        }
    }

    if ($Utils::checkSession() && $Utils::checkToken()) {
        $User = new User($Pdo, $_SESSION['user']);
        $User->main();
    }