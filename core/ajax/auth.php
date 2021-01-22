<?php
    require realpath('../db.php');
    require realpath('../utils.php');

    Class Auth {

        public function __construct($pdo) 
        {

            $this->pdo = $pdo;

        }
        
        public function mail_verif() {

            if (verifMail( $this->mail )) {
                $find_mail = $this->pdo->fetch('SELECT `id` FROM `users` WHERE `mail` = ?', array( $this->mail ));

                if ($find_mail) {
                    $this->message = 'Данный email уже занят!';
                }
            } else {
                $this->message = 'Неправильный формат email!';
            }
            
        }

        public function name_verif() {

            if (verifName( $this->name )) {
                $find_name = $this->pdo->fetch('SELECT `id` FROM `users` WHERE `login` = ?', array( $this->name ));

                if ( $find_name ) {
                    $this->message = 'Данный ник уже занят!';
                }
            } else {
                $this->message = 'Неправильный формат ника!';
            }

        }

        public function pass_verif() {

            if (!verifPass($this->pass)) {
                $this->message = 'Неправильный формат пароля!';
            }

        }
        
        public function reg() {

            $newuser = $this->pdo->query('INSERT INTO users (login, pass, mail, date, lastvisit, ban, adm, live, costumize, craft_lvl, in_refuge) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            array($this->name, password_hash($this->pass, PASSWORD_DEFAULT), password_hash($this->mail, PASSWORD_DEFAULT), time(), 0, 0, 0, 3, 0, 1, 0));
            $userid = $this->pdo->last();

            $game   = $this->pdo->query('INSERT INTO game (data, user_id) VALUES (?, ?)',
            array(json_encode(['x' => 50, 'y' => 50, 's' => 100, 'time' => 36000, 'weather' => 1, 'hp' => 100, 'hung' => 0, 'thirst' => 0, 'fatigue' => 0, 'temp' => 1, 'loc' => 1, 'loc_explored' => 0, 'weatherTime' => 0, 'hpTime' => 0, 'hungTime' => 0, 'thirstTime' => 0, 'fatigueTime' => 0]), $userid));
            $refuge = $this->pdo->query('INSERT INTO refuge (hp, lvl, user_id) VALUES (?, ?, ?)', array(0, 0, $userid));
            $nadeto = $this->pdo->query('INSERT INTO nadeto (helm, arm, weap, hair, beard, cloth, pants, fwear, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', array(0, 0, 0, 1, 1, 1, 1, 1, $userid));

            $food   = $this->pdo->query('INSERT INTO ivent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(13, 1, 5, $userid));
            $water  = $this->pdo->query('INSERT INTO ivent (item, type, colvo, user_id) VALUES (?, ?, ?, ?)', array(2, 1, 5, $userid));

            $_SESSION['token'] = token();
            $_SESSION['user']  = $userid;
            
            $this->answer('page', '/costumize');
            
        }
        
        public function enter() {

            $find_user = $this->pdo->fetch('SELECT * FROM `users` WHERE `login` = ?', array($this->name));

            if ($find_user && password_verify($this->pass, $find_user['pass'])) {
                if ($find_user['ban'] > time()) {
                    $ban_time = date('d.m.Y H:i:s', $find_user['ban']);

                    $this->message = 'Вы заблокированы до - <div class=\'flex j-c mt5 mb5\'>'.$ban_time.'</div>';
                    $this->answer('mess', 0);
                } else {
                    $_SESSION['token'] = token();
                    $_SESSION['user']  = $find_user[ 'id' ];
                    $this->answer('page', '/');
                }
            } else {
                $this->message = 'Нeверный ник или пароль!';
                $this->answer('mess', 0);
            }

        }

        public function answer($ans, $page) {

            switch( $ans ) {
                case 'page':
                    exit( json_encode( ['page' => $page, 'token' => $_SESSION['token']] ) );
                break;
                case 'mess':
                    exit( json_encode( ['message' => $this->message, 'popup' => true] ) );
                break;
            }

        }

        public function main() {

            switch($_GET['action']) {
                case 'reg':
                    $this->name = htmlspecialchars( trim( $_POST['name'] ) );
                    $this->pass = htmlspecialchars( trim( $_POST['pass'] ) );
                    $this->mail = htmlspecialchars( trim( $_POST['mail'] ) );

                    $this->mail_verif();
                    $this->pass_verif();
                    $this->name_verif();

                    if (empty( $this->message )) $this->reg();
                    else $this->answer('mess', 0);
                break;
                case 'enter':
                    $this->name = htmlspecialchars( trim( $_POST['name'] ) );
                    $this->pass = htmlspecialchars( trim( $_POST['pass'] ) );

                    $this->enter();
                break;
            }

        }
    }

    $Auth = new Auth($pdo);
    $Auth->main();