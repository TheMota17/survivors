<?php
    require 'db.php';
    require 'utils.php';

    Class Sys {
        private $pdo;

        private $message;

        private $user;
        private $id;

        public function __construct($pdo, $id)
        {

            $this->pdo = $pdo;

            $this->id = htmlspecialchars( intval( $id ) );

        }

        public function user() {

            if (!$this->id) $this->exit('auth');

            $this->user = $this->pdo->fetch('SELECT * FROM `users` WHERE `id` = ?', array( $this->id ));
            $this->game = $this->pdo->fetch('SELECT * FROM `game` WHERE `user_id` = ?', array($this->user['id']));
            $this->game = json_decode( $this->game['data'] );

            $this->ban();
            $this->costumize();
            $this->visit();
        }

        public function get_user() {

            return $this->user;

        }

        public function user_info($type, $data) {

            switch($type) {
                case 'userinfo':
                    return $this->user[ $data ];
                break;
                case 'gameinfo':
                    return $this->game->$data;
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

            if ($this->user[ 'costumize' ] > 0) return true;
            else $this->exit('costumize');

        }

        public function visit() {

            $this->pdo->query('UPDATE users SET `lastvisit` = ? WHERE `id` = ?', array(time(), $this->user['id']));

        }
 
        public function exit( $move ) {

            switch( $move) {
                case 'auth':
                    exit('
                        <div class=\'flex j-c mt10\'>
                            <a href=\'/auth\' class=\'ajax\'>Авторизуйтесь</a>
                        </div>
                    ');
                break;
                case 'costumize':
                    exit('
                        <div class=\'flex j-c mt10\'>
                            <a href=\'/costumize\' class=\'ajax\'>Кастомизировать</a>
                        </div>
                    ');
                break;
                case 'ban':
                    exit('
                        <div class=\'flex j-c mt10\'>
                            <a href=\'/auth\' class=\'ajax\'>Вы заблокированы до - '.$this->message.'</a>
                        </div>
                    ');
                break;
            }

        }

        public function main() {
            
            $this->user();

        }
    }

    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        if ($_SESSION['token'] == $_POST['token'] && $_POST['token'] && $_SESSION['token']) {
            $Sys = new Sys($pdo, $_SESSION['user']);
            $Sys->main();
        } else exit;
    } else exit;