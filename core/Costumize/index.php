<?php
    require realpath('../db.php');
    require realpath('../Utils.php');

    Class Costumize {

        public function __construct($pdo, $hair, $beard, $cloth, $pants, $fwear) 
        {

            $this->pdo = $pdo;

            $this->user = $this->pdo->fetch('SELECT * FROM `users` WHERE `id` = ?', array( $_SESSION['user'] ));

            $this->hair  = htmlspecialchars( intval( $hair ) );
            $this->beard = htmlspecialchars( intval( $beard ) );
            $this->cloth = htmlspecialchars( intval( $cloth ) );
            $this->pants = htmlspecialchars( intval( $pants ) );
            $this->fwear = htmlspecialchars( intval( $fwear ) );

        }
        
        public function save() {

            if ($this->user[ 'costumize' ] == 0) {
                $this->pdo->query('UPDATE `nadeto` SET `hair` = ?, `beard` = ?, `cloth` = ?, `pants` = ?, `fwear` = ? WHERE `user_id` = ?', 
                array($this->hair, $this->beard, $this->cloth, $this->pants, $this->fwear, $this->user['id']));
                
                $this->pdo->query('UPDATE `users` SET `costumize` = ? WHERE `id` = ?', array(1, $this->user['id']));
            }
            $this->answer('page', '/');
            
        }

        public function answer($ans, $page) {

            switch( $ans ) {
                case 'page':
                    exit( json_encode( ['page' => $page] ) );
                break;
                case 'mess':
                    exit( json_encode( ['message' => $this->message, 'popup' => true] ) );
                break;
            }

        }

        public function main() {

            $this->save();

        }
    }

    if ($Utils::checkSession() && $Utils::checkToken()) {
        $Costumize_save = new Costumize($Pdo, $_POST['hair'], $_POST['beard'], $_POST['cloth'], $_POST['pants'], $_POST['fwear']);
        $Costumize_save->main();
    }